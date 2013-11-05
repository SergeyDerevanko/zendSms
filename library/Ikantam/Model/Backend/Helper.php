<?php
class Ikantam_Model_Backend_Helper extends Ikantam_Model_Backend_Abstract {

    protected $_table = 'sys_helpers';



    /* COLLECTION */
    public function getByType(\Ikantam_Model_Collections_Abstract $object, $module){
        $select = $this->select()
            ->where('type = ?', $module);
        $this->fetchAll($object, $select);
    }



    /* OBJECT */
    public function getByTypeAndName(\Ikantam_Model_Abstract $object, $type, $name){
        $select = $this->select()
            ->where('type = ?', $type)
            ->where('name = ?', $name);
        $this->fetch($object, $select);
    }
}
