<?php
<<<<<<< HEAD
class User_Form_Create extends Ikantam_Form {
    
    public function init ()
    {
        $prefix = Ikantam_Model::getPrefix();
=======
class User_Form_Create extends Zend_Form {
    
    public function init ()
    {
>>>>>>> d2e2a8320bc53cc686264c816a485054612e4a95
        $baseFilters = array('StringTrim', 'StripTags');

        
        $lengthValidator = new Zend_Validate_StringLength(array('max' => 45));
        $lengthValidator->setMessage('Value must be less than %max%' , Zend_Validate_StringLength::TOO_LONG);

<<<<<<< HEAD

        $email = $this->createElement('text', 'email');
        $email->setRequired()
              ->addFilters($baseFilters)
              ->addValidator('EmailAddress')
              ->addValidator($lengthValidator)
              ->addValidator(new Zend_Validate_Db_NoRecordExists(array(
                'table' => "{$prefix}users",
                'field' => 'email'
              )));
=======
        $email = $this->createElement('text', 'email');
        $email->setRequired()
              ->addFilter('StringTrim')  
              ->addValidators(array('EmailAddress'));
>>>>>>> d2e2a8320bc53cc686264c816a485054612e4a95
              

        $password = $this->createElement('text', 'password');
        $password
<<<<<<< HEAD
            ->setRequired()
            ->addFilters($baseFilters)
            ->addValidator($lengthValidator);

        $conf_password = $this->createElement('text', 'conf_password');
        $conf_password
            ->addFilters($baseFilters)
            ->setRequired()
            ->addValidator(new Zend_Validate_Identical('password'))
            ->addValidator($lengthValidator);

        $id = $this->createElement('text', 'id');
=======
            ->addFilter('StringTrim')
            ->setRequired('true');

        $conf_password = $this->createElement('text', 'conf_password');
        $conf_password
            ->addFilter('StringTrim')
            ->setRequired('true')
            ->addValidator(new Zend_Validate_Identical('password'));

        $id = $this->clearElements('id', 'id');
>>>>>>> d2e2a8320bc53cc686264c816a485054612e4a95

        $this->addElements(array(
            $email,
            $password,
            $conf_password,
            $id
        ));
<<<<<<< HEAD
=======
              
                    
>>>>>>> d2e2a8320bc53cc686264c816a485054612e4a95
    }  
}