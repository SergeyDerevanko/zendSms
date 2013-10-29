<?php

class User_Model_Collections_Socials extends Ikantam_Model_Collections_Abstract
{

    public function getByUserId($userId){
        $this->_getBackend()->getByUserId($this, $userId);
        return $this;
    }
}