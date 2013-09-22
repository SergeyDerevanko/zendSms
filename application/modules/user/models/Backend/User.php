<?php
class User_Model_Backend_User extends Ikantam_Model_Backend_Abstract
{
    protected  $_table = 'users';

    /* OBJECT */
    public function getByEmail(\Ikantam_Model_Abstract $object, $email){
        $select = $this->select()->where('email = ?', $email);
        $this->fetch($object, $select);
    }
}