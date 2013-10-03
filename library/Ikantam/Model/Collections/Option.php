<?php
class Ikantam_Model_Collections_Option extends Ikantam_Model_Collections_Abstract
{
    /* FIND PUBLIC FUNCTION */
    public function getByType($type){
        $this->_getBackend()->getByType($this, $type);
        return $this;
    }


    public function getArrayByType($type){
        $this->getByType($type);
        return $this->getArrayValue();
    }


    /* GET PUBLIC FUNCTION */
    public function getArrayValue(){
        $data = array();
        foreach($this as $option){
            $data[$option->getName()] = $option->getValue();
        }
        return $data;
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
}
