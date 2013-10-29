<?php
class Ikantam_Model_Collections_Helper extends Ikantam_Model_Collections_Abstract {



    /* FIND PUBLIC FUNCTION */
    public function getByType($type){
        $this->_getBackend()->getByType($this, $type);
        return $this;
    }
}
