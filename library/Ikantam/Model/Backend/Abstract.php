<?php

abstract class Ikantam_Model_Backend_Abstract
{
    protected $db = null;
    static $_describe = array();
    static $_columns = array();

    protected $_table = '';
    protected $_related_table = null;
    /* array(
        'related_col', 'col', 'table_name'
    )*/



    function __construct(){
        if(!$this->db)
            $this->setDb(Ikantam_Model::getConnect());
    }


    public function getDb(){
        return $this->db;
    }


    public function getPrefix(){
        return Ikantam_Model::getPrefix();
    }


    public function setDb($db){
        $this->db = $db;
    }


    public function getTable(){
        return $this->getPrefix() . $this->_table;
    }


    public function describeTable(){
        if(!isset(self::$_describe[$this->getTable()])){
            $db = $this->getDb();
            self::$_describe[$this->getTable()] = $db->describeTable($this->getTable());
        }
        return self::$_describe[$this->getTable()];
    }


    public function getColumns(){
        if(!isset(self::$_columns[$this->getTable()])){
            foreach($this->describeTable() as $key => $val){
                self::$_columns[$this->getTable()][] = $key;
            }
        }
        return self::$_columns[$this->getTable()];
    }


    public function relatedSelect($name = null, $cols = '*', $schema = null){
        $name = $name ? $name : $this->getTable();
        $select = $this->select($name, $cols, $schema);
        if($this->_related_table){
            $select->join(
                array('related' => $this->getPrefix() . $this->_related_table['table_name']),
                $name . '.' . $this->_related_table['col'] . ' = related.' . $this->_related_table['related_col'],
                array()
            );
        }
        return $select;
    }


    public function select($name = null, $cols = '*', $schema = null){
        $name = $name ? $name : $this->getTable();
        $select = $this->getDb()->select()->from($name, $cols, $schema);
        return $select;
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


    public function fetchAll(\Ikantam_Model_Collections_Abstract $object, $select){
        $rows = $this->findAll($select);
        if($rows)
            $object->addData($rows);
    }


    public function fetch(\Ikantam_Model_Abstract $object, $select){
        $row = $this->find($select);
        if($row)
            $object->setData($row);
    }


    public function getById(\Ikantam_Model_Abstract $object, $id){
        $select = $this
            ->select()
            ->where('id = ?', $id);

        $this->fetch($object, $select);
    }


    public function getAll(\Ikantam_Model_Collections_Abstract $object){
        $select = $this
            ->select();
        $this->fetchAll($object, $select);
    }


    public function insert(\Ikantam_Model_Abstract $object){
        $db = $this->getDb();
        $data = $this->_clearData($object);
        $db->insert($this->getTable(), $data);
        $object->setId($db->lastInsertId());
    }


    public function update(\Ikantam_Model_Abstract $object){
        $db = $this->getDb();
        $data = $this->_clearData($object);
        $db->update($this->getTable(), $data, 'id=' . $object->getId());
    }


    public function delete(\Ikantam_Model_Abstract $object){
        $db = $this->getDb();
        $db->delete($this->getTable(), 'id = ' . $object->getId());
    }



    /* PRIVATE FUNCTION */
    protected function _clearData(\Ikantam_Model_Abstract $object){
        $_data = array();
        foreach($object->getData() as $index => $value){
            if(in_array($index, $this->getColumns()))
                $_data[$index] = $value;
        }
        return $_data;
    }


    protected function _prepare($sql, $params = array()){
        $db = $this->getDb();
        $stmt = $db->prepare($sql);
        foreach($params as $index => $param){
            $stmt->bindParam(':'. $index, $param);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



}
