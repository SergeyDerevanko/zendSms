<?php
/**
 * Abstract model
 *
 * @method int getId()
 *
 */
abstract class Application_Model_Abstract extends Ikantam_Object
{
    protected $_events ; //Zend_EventManager_EventCollection
    protected $_flags = array();
    protected $_fieldNamesArray = array();
    /**
     * Contains errors -  failed validation
     * structure: ('field' => array('label' => 'error message'))
     * @type array
     */
    protected $_errors = array ();

    /**
     * Describes selectors for fields. Use in JS(jQuery)
     * @type array
     * structure: ('field' => 'selector') | ('name' => '#inputs input[name="name"]')
     */
    protected $_selectors = array ();

    /**
     * @type string - name of Zend_Form class or auto
     * If set to auto object validator will be created automatically
     * Class use for validation.
     * Class must contain isValid(array $data) method
     * isValid() always true if this param empty
     */
    protected $_validationClass ;

    /**
     * Instance of validation class
     * @type Zend_Form object
     */
    private $_validationClassInstance ;
    
    /**
     * Hold validators
     * @type array
     */
    protected $_temporaryValidators = array ();

    /**
     * List of temporary required fields and temorary not required
     * @type array
     */
    protected $_temporaryRequiredFields = array (); // array('required' => array('field1', 'field2'..),  'not_required' => array('field5', 'field8'..))
    protected $_tmpValidatorInstance ;

    


    public function __construct($id = null){
        $fieldNames = $this->_getBackend()->getFieldNames();
        foreach($fieldNames as $fieldName) {
            if(!isset($this->_selectors[$fieldName])) {
                $this->_selectors[$fieldName] =  '#'.$fieldName;
            }
        }

        if (is_numeric($id)) {
            $this->getById($id);
        }

        if (is_array($id)) {
            $this->setData($id);
        }

        $this->_init();
    }




    protected function _getValidationClassInstance($params = null)
    {
        //new instance for new params
        if(!is_null($params)) {
            $this->_validationClassInstance = null;
        }

        if($this->_validationClassInstance instanceof Zend_Form) {
            return $this->_validationClassInstance;
        }

        $this->_validationClass = (string) $this->_validationClass;
        if($this->_validationClass == 'auto') {
            $this->_validationClassInstance =  $this->__createValidator__();
            return $this->_validationClassInstance;
        }

        if(is_string($this->_validationClass) && !empty($this->_validationClass) && $this->_validationClass != 'auto') {
            $instance = new $this->_validationClass($params);
            if(!$instance instanceof Zend_Form) {
                throw new Exception('Class "'.$this->_validationClass.'" must be an instance of Zend_Form');
            }

            $this->_validationClassInstance = $instance;
            return $this->_validationClassInstance;
        }

        return null;
    }













    public function getById($id){
        $this->_getBackend()->getById($this, $id);
        return $this;
    }


    public function save(){
        $this->_errors = array();        

        if($this->_flag('___skip_validation___') || $this->isValid()) {

            $vc = $this->_getValidationClassInstance();

            if($vc && !$this->_flag('___skip_validation___')) {
                $this->_data = $vc->getValues();
            }
            $this->_beforeSave(); // after validation. Exactly before save
            $this->_getBackend()->save($this);

        }

        if($this->_flag('___skip_validation___')) {
            $this->_toogleFlag('___skip_validation___');
        }

        $this->_afterSave();
        return $this;
    }

    public function delete(){
        $this->setIsDeleted(true)->save()->unsId();
    }



    public function __call($method, $args){
        if (substr($method, 0, 6) == 'getBy_') {
            $field = substr($method, 6, strlen($method));
            $this->_getBackend()->getByFieldValue__($this, $field, $args[0]);
            return $this;
        } else {
            return parent::__call($method, $args);
        }
    }


    /** Return object data. This method allows to use Zend_Json::encode($object)
     * array $columns -  you can specify fields | array('id', 'name')
     * @return array
     */
    public function toArray (array $fields = array ())
    {
        if($fields) {
            $result = array();
            foreach($fields as $field) {
                if(key_exists($field, $this->_data)) {
                    $result[$field] = $this->_data[$field];
                }
            }

            return $result;
        }
        return $this->_data;
    }

    /* VALIDATION */
    /**
     * Add validation property
     * @param string $field
     * @param mixed $validator - Zend_Validate_Interface | string(validator name or "required" to set field required)
     * @param array $options - for validation contructor
     * @return self
     */
    public function addValidationProperty ($field, $validator, $options = null)
    {
        if(!is_string($field) || empty($field)) {
            throw new Exception(__METHOD__.' $field must be a string.');
        }

        if(is_string($validator)) {
            if($validator != 'required' && $validator != 'not_required') {
                $validator = 'Zend_Validate_'.$validator;
                $validator = new $validator($options);
                if(isset($options['error_message'])) {
                    $validator->setMessage($options['error_message']);
                }
                $this->_temporaryValidators[$field][] = $validator;
                return $this;
            } else {
                if($validator == 'required') {
                    $this->_temporaryRequiredFields['required'][] = array('field' => $field, 'options' => $options);
                } else {
                    $this->_temporaryRequiredFields['not_required'][] = $field;
                }

                return $this;
            }
        }elseif(!$validator instanceof Zend_Validate_Interface) {
            throw new Exception(get_called_class().'::addTemporaryValidationProperty - $validator must be a string or Zend_Validate_Interface');
        }

        $this->_temporaryValidators[$field][] = $validator;
        return $this;
    }

    public function getTmpValidator()
    {
        $tmpForm = new Zend_Form();
        foreach($this->_temporaryValidators as $field => $validators)
        {
            $element = new Zend_Form_Element($field);
            $element->addValidators($validators);
            $tmpForm->addElement($element);
        }

       $notEmpty = new Zend_Validate_NotEmpty();       

        if(isset($this->_temporaryRequiredFields['required'])) {
            foreach($this->_temporaryRequiredFields['required'] as $data) {
                $field = $data['field'];
                $options = $data['options'];
                $element = $tmpForm->getElement($field);

                if($element) {
                    $tmpForm->{$field}->setRequired();
                    if(isset($options['error_message'])) {
                        $notEmpty->setMessage($options['error_message']);
                        $tmpForm->{$field}->addValidator($notEmpty);
                    }
                } else {
                    $element = new Zend_Form_Element($field);
                    $element->setRequired();
                    
                    if(isset($options['error_message'])) {
                        $notEmpty->setMessage($options['error_message']);
                        $element->addValidator($notEmpty);
                     }
                     
                    $tmpForm->addElement($element);
                }
            }
        }

        $standartValidator = $this->getValidator();

        if(isset($this->_temporaryRequiredFields['not_required'])) {
            foreach($this->_temporaryRequiredFields['not_required'] as $field) {
                $element = $tmpForm->getElement($field);
                if($element) {
                    $tmpForm->{$field}->setRequired(false);
                }

                if($standartValidator) {
                    $element = $standartValidator->getElement($field);
                    if($element) {
                        $standartValidator->{$field}->setRequired(false);
                    }
                }
            }
        }

        return $tmpForm;
    }



    public function getValidator ()
    {
        return $this->_getValidationClassInstance();
    }

    public function isValid ($params = null)
    {
        $validator = $this->_getValidationClassInstance($params);
        $tmpValidator = $this->getTmpValidator();

        if(!$validator && !$tmpValidator) {
            return true; 
        }

        if($tmpValidator) {
            if(!$tmpValidator->isValid($this->_data)) {
                $this->addErrorMessage($tmpValidator->getMessages());
                if(!$validator) {
                    return false;
                }
            } elseif(!$validator) { 
                return true; 
            }

        }

        $result = $validator->isValid($this->_data);
        if(!$result) {
            $this->addErrorMessage($validator->getMessages());
        }

        /*if($this->isExists()) {
            foreach($this->_errors as $key => $value) {
                unset($this->_errors[$key]['recordFound']);
                if(!$this->_errors[$key]) {
                    unset($this->_errors[$key]);
                }
            }

            $result = !(bool)$this->_errors;
        }*/
        $result = !(bool)$this->_errors;
        return $result;
    }


    public function getLoginUser(){
        return User_Model_Session::instance()->user();
    }


    public function getLoginUserId(){
        return $this->getLoginUser()->getId();
    }


    public function isLogin(){
        return User_Model_Session::instance()->isLoggedIn();
    }


    public function isAdmin(){
        return true;
    }


    protected function getPublicUrl($path){
        return Ikantam_Lib_Url::getPublicUrl($path);
    }


    protected function getUrl($path = '', $params = array(), $reset = true, $configPath = true){
        return Ikantam_Lib_Url::getUrl($path, $params, $reset, $configPath);
    }


    protected function getRouteUrl($routName, $params = array()){
        return Ikantam_Lib_Url::getRouteUrl($routName, $params);
    }


    abstract protected function _getBackend();
    protected function _beforeSave(){}
    protected function _afterSave(){}
    protected function _init() {}




    ///* VALIDATOR FUNCTIONS *///
    private function __createValidator__() {
        $form = new Zend_Form();
        $filters =  array(new Zend_Filter_StringTrim, new Zend_Filter_StripTags);

        foreach($this->_getBackend()->getDescribedFields() as $fieldInfo) {

            $zendField = new Zend_Form_Element($fieldInfo['Field']);

            if(!$fieldInfo['Null'] && !$fieldInfo['Identity']) {
                $zendField->setRequired();
            }

            if(preg_match('/(big|medium|small|tiny)?int/', $fieldInfo['Type'])) {
                $zendField->addValidator('Digits');
                $zendField->addValidator('Int');

            }
            elseif(in_array($fieldInfo['Type'], array('decimal', 'double', 'float'))) {
                $zendField->addValidator('Digits');
                $zendField->addValidator('Float');
            }
            elseif(in_array(strtolower($fieldInfo['Field']), array('email', 'e-mail', 'e-mails','emails', 'email-address'))) {
                $zendField->addValidator('EmailAddress');
            }

            if(in_array($fieldInfo['Type'], array('enum', 'set'))) {
                $inArrayValidator = new Zend_Validate_InArray($fieldInfo['Values']);
                $zendField->addValidator($inArrayValidator);
            }elseif($fieldInfo['Type'] == 'varchar') {
                $lengthValidator = new Zend_Validate_StringLength(array('max' => $fieldInfo['Length']));
                $zendField->addValidator($lengthValidator);
            }

            if(!in_array($fieldInfo['Field'], array('password', 'pass', 'pwd'))) {
                $zendField->addFilters($filters);
            }

            if($fieldInfo['Unsigned']) {
                $greaterThanValidator = new Zend_Validate_GreaterThan(array('min' => 0));
                $zendField->addValidator($greaterThanValidator);
            }

            if($fieldInfo['Key'] == 'UNI') {
                $uniqValidator = new Zend_Validate_Db_NoRecordExists(array(
                    'table' => $this->_getbackend()->_getTable(),
                    'field' => $fieldInfo['Field']
                ));

                $zendField->addValidator($uniqValidator);
            }

            $form->addElement($zendField);
        }

        return $form;
    }


    public function getErrorsSelectorsJson (){
        $result = array();
        foreach($this->_selectors as $field => $selector) {
            $errors = $this->getErrorMessages($field);
            if(is_array($errors)) {
                $result[$selector]['error_string'] = implode('. ', $errors);
                $result[$selector]['errors'] = $errors;
            }
        }
        return Zend_Json::encode($result);
    }


    public function addErrorMessage ($field, $label = null, $message = null){
        if(is_array($field)) {
            $this->_errors = array_merge_recursive($this->_errors, $field);
            return $this;
        } elseif (is_string($field) && is_string($label) && is_string($message)) {
            $this->_errors[$field][$label] = $message;
        }
        return $this;
    }


    public function getErrorMessages ($field = null){
        if(!$field) {
            return $this->_errors;
        } elseif (isset($this->_errors[(string)$field])) {
            return $this->_errors[$field];
        }
        return null;
    }

    public function skipValidation (){
        $this->_flag('___skip_validation___', true);
        return $this;
    }


    protected function getFieldNames(){
        if(!$this->_fieldNamesArray)
            $this->_fieldNamesArray = $this->_getBackend()->getFieldNames();
        return $this->_fieldNamesArray;
    }





    ///* FLAG FUNCTION *///
    protected function _toogleFlag($flag){
        return $this->_flags[$flag] = (isset($this->_flags[$flag])) ? !$this->_flags[$flag] : true;
    }


    protected function _flag($flag, $state = null){
        if(is_bool($state)) {
            $this->_flags[$flag] = $state;
            return $state;
        }
        elseif(is_null($state)) {
            if($this->_isFlagDefined($flag)) {
                return $this->_flags[$flag];
            }
        }
        return null;
    }


    protected function _isFlagDefined($flag){
        return isset($this->_flags[$flag]);
    }
    
//----------------------------------------------------------------------------
   public function events(Zend_EventManager_EventCollection $events = null)
    { 
        if (null !== $events) {
            $this->_events = $events;
        } elseif (null === $this->_events) {
            $this->_events = new Zend_EventManager_EventManager(get_called_class());
        }
        return $this->_events;
    }     
}
