<?php
class User_Model_Backend_User extends Ikantam_Model_Backend_Abstract
{
    protected  $_table = 'users';

    /* OBJECT */
    public function getByEmail(\Ikantam_Model_Abstract $object, $email){
        $select = $this->select()->where('email = ?', $email);
        $this->fetch($object, $select);
    }


    public function addGroup($userId, $groupId){
        $prefix = $this->getPrefix();
        $sql = "INSERT {$prefix}user_user_group (`user_id`, `user_group_id`) VALUE (:user_id, :user_group_id)";
        $this->prepare($sql, array(
            'user_id' => $userId,
            'user_group_id' => $groupId
        ));
    }


    public function deleteGroup($userId){
        $prefix = $this->getPrefix();
        $sql = "DELETE FROM {$prefix}user_user_group WHERE user_id = :user_id";
        $this->prepare($sql, array(
            'user_id' => $userId
        ));
    }
}