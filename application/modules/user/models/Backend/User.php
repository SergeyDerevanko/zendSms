<?php
class User_Model_Backend_User extends Ikantam_Model_Backend_Abstract
{
    protected  $_table = 'users';

    /* OBJECT */
    public function getByEmail(\Ikantam_Model_Abstract $object, $email){
        $select = $this->select()->where('email = ?', $email);
        $this->fetch($object, $select);
    }


    public function getBySocial(\Ikantam_Model_Abstract $object, $identifier, $provider){
        $_pref = $this->getPrefix();
        $table = $this->getTable();
        $select = $this->select()
            ->join(array('user_socials' => $_pref . 'user_socials'), 'user_socials.user_id = ' . $table . '.id')
            ->where('user_socials.type = ?', $provider)
            ->where('user_socials.social_id = ?', $identifier);

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