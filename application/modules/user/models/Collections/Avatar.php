<?php
class User_Model_Collections_Avatar extends Ikantam_Model_Collections_Abstract
{
    public function getAllTmp(){
        $this->_getBackend()->getAllTmp($this);
        return $this;
    }
}