<?php
class User_Model_Backend_Avatar extends Ikantam_Model_Backend_Abstract {

    protected $_table = "user_avatar";



    /* COLLECTION */
    public function getAllTmp(\User_Model_Collections_Avatar $object){
        $select = $this->select()->where('flag_tmp = ?', 1);
        $this->fetchAll($object, $select);
    }
}