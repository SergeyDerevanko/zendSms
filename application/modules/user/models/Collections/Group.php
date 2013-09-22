<?php

class User_Model_Collections_Group extends Ikantam_Model_Collections_Abstract
{
    /* FIND PUBLIC FUNCTION */
    public function getByUserId($userId){
        $this->_getBackend()->getByUserId($this, $userId);
        return $this;
    }
}