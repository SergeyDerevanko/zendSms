<?php

class User_Model_Backend_Group extends Ikantam_Model_Backend_Abstract
{
    protected $_table = 'user_groups';
    protected $_related_table = array(
        'table_name' => 'user_user_group',
        'col' => 'id',
        'related_col' => 'user_group_id'
    );


    /* OBJECT */
    public function getByUserId(\Ikantam_Model_Collections_Abstract $object, $userId){
        $select = $this->relatedSelect()->where('related.user_id = ?', $userId);
        $this->fetchAll($object, $select);
    }
}