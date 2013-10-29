<?php
class Ikantam_Model_Helper extends Ikantam_Model_Abstract {

    public function __construct($type = null, $module = null, $path = null, $name = null){
        parent::__construct();
        if($type && $module && $path && $name){
            $this->getByTypeAndName($type, $name);
        }

        if(!$this->getId()){
            $this->setType($type)
                ->setModule($module)
                ->setPath($path)
                ->setName($name);

            $this->save();
        }
        return $this;
    }



    /* FIND PUBLIC FUNCTION */
    public function getByTypeAndName($type, $name){
        $this->_getBackend()->getByTypeAndName($this, $type, $name);
        return $this;
    }
}
