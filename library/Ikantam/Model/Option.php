<?php

class Ikantam_Model_Option extends Ikantam_Model_Abstract
{
    public function __construct($type = null, $name = null, $value = null){
        parent::__construct();
        if($type && $name){
            $this->getByTypeAndName($type, $name);
        }

        if($value !== null && !$this->getValue()){
            $this->setValue($value);
            $this->save();
        }
    }



    /* FIND PUBLIC FUNCTION */

    public function getByTypeAndName($type, $name){
        $this->_getBackend()->getByTypeAndName($this, $type, $name);
        if(!$this->getId())
            $this->settype($module)
                ->setName($name)
                ->save();
        return $this;
    }
}
