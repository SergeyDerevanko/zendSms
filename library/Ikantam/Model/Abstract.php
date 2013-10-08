<?php

abstract class Ikantam_Model_Abstract extends Ikantam_Object
{
    protected $_validClass = null;
    protected $_errors = null;
    protected $_classBackend = null;


    protected function beforeValid(){}
    protected function beforeSave(){}
    protected function afterSave(){}


    public function __construct($id = null){
        $className = explode('_', get_class($this));
        $this->_classBackend = $className[0] . '_Model_Backend_' . end($className);
        if (is_numeric($id)) {
            $this->getById($id);
        }

        $this->_errors = new Ikantam_Model_Errors();
    }


    public function save(){
        $this->beforeValid();
        if($this->isValid()){
            $this->beforeSave();
            try {
                if($this->getId()){
                    $this->_getBackend()->update($this);
                } else {
                    $this->_getBackend()->insert($this);
                }
                return $this;
            } catch (Exception $e) {
                return false;
            }
            $this->afterSave();
        }
        return false;
    }


    public function isValid(){
        $class = $this->getValidClass();
        $validObject = new $class();
        $validObject->setValidatorDescribe($this->_getBackend()->describeTable());
        $isValid = $validObject->isValid($this->getData());
        if(!$isValid){
            foreach($validObject->getErrors() as $index => $value){
                foreach($value as $error){
                    $this->addTextError($index, $error);
                }
            }
        }
        return $isValid;
    }


    public function getValidClass(){
        return $this->_validClass ? $this->_validClass : new Ikantam_Form();
    }


    public function setValidClass($class){
        $this->_validClass = $class;
        return $this;
    }


    public function delete(){
        $this->_getBackend()->delete($this);
        $this->setData(array());
        return $this;
    }


    public function getById($id){
        $this->_getBackend()->getById($this, $id);
        return $this;
    }


    public function addTextError($index, $text){
        $this->_errors->addText($index, $text);
        return $this;
    }


    public function addObjectError($index, \Ikantam_Object_Message_Error $object){
        $this->_errors->addObject($index, $object);
        return $this;
    }


    public function getErrorsGrouping($typeGrouping = 'fields_string'){
        return $this->_errors->getErrorsGrouping($typeGrouping);
    }

    public function getErrorsString(){
        return $this->_errors->getErrorsGrouping('string');
    }

    public function getErrorsArray(){
        return $this->_errors->getErrorsGrouping();
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



    protected function _getBackend(){
        return new $this->_classBackend();
    }


    public function getObjectOptions($type){
        return Ikantam_Option::getObjectOptions($type);
    }


    public function getOption($type, $name, $value = ''){
        return Ikantam_Option::getOption($type, $name, $value);
    }


    public function getOptions($type){
        return Ikantam_Option::getOptions($type);
    }


    public function __call($methodName, $args){
        $reaction = Ikantam_Lib_System_Reaction::call($methodName, $args);
        if($reaction !== null)
            return $reaction;

        return parent::__call($methodName, $args);
    }
}
