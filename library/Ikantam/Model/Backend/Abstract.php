<?php

abstract class Ikantam_Model_Model_Backend_Abstract
{
    protected $db = null;
    static $_describe = array();

    protected $_table = '';



    function __construct(){
        if(!$this->db)
            $this->setDb(Ikantam_Db::getConnect());
    }


    public function getDb(){
        return $this->db;
    }


    public function setDb($db){
        $this->db = $db;
    }

    public function describeTable(){
        if(!isset(self::$_describe[$this->_table])){
            $db = $this->getDb();
            self::$_describe[$this->_table] = $db->describeTable($this->_table);
        }
        return self::$_describe[$this->_table];
    }


    public function select($name = null, $cols = '*', $schema = null){
        $name = $name ? $name : $this->_table;
        return $this->getDb()->select()->from($name, $cols = '*', $schema = null);
    }


    private function getStmt($select){
        return $this
            ->getDb()
            ->query($select);
    }


    public function find($select){
        $stmt = $this->getStmt($select);
        return $stmt->fetch();
    }


    public function findAll($select){
        $stmt = $this->getStmt($select);
        return $stmt->fetchAll();
    }


    public function fetchAll(\Application_Model_Collections_Abstract $object, $select){
        $rows = $this->findAll($select);
        if($rows)
            $object->addData($rows);
    }


    public function fetch(\Application_Model_Abstract $object, $select){
        $row = $this->find($select);
        if($row)
            $object->setData($row);
    }


    public function getById(\Application_Model_Abstract $object, $id){
        $select = $this
            ->select()
            ->where('id = ?', $id);

        $this->fetch($object, $select);
    }


    public function getAll(\Application_Model_Collections_Abstract $object){
        $select = $this
            ->select();

        $this->fetchAll($object, $select);
    }



}
