<?php

class User_Model_Backend_Socials extends Ikantam_Model_Backend_Abstract
{
    protected $_table = 'user_socials';


    /* OBJECT */
    public function getByUserId(\Ikantam_Model_Collections_Abstract $object, $userId){
        $select = $this->select()->where('user_id = ?', $userId);
        $this->fetchAll($object, $select);
    }
}