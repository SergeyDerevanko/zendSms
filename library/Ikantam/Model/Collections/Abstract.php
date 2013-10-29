<?php

abstract class Ikantam_Model_Collections_Abstract extends Ikantam_Collection
{

	protected $_classBackend = null;
    protected $_classItem = null;

    private $_allCount = null;

	public function __construct(){
        $className = explode('_', get_class($this));

        $this->_classBackend =  $className[0] . '_Model_Backend_' . end($className);
        $this->_classItem = $className[0] . '_Model_' . end($className);

		parent::__construct();
	}


    public function addData($data){
        foreach($data as $value){
            $item = $this->_getItemObject();
            $item->setData($value);
            $this->addItem($item);
        }
    }


    public function getAll(){
        $this->_getBackend()->getAll($this);
        return $this;
    }


    public function getAllCount(){
        return $this->_allCount === null ? 0 : $this->_allCount;
    }


    public function getStringImplodeColumn($column, $imp = ','){
        $array = $this->getArrayColumn($column);
        return implode($imp, $array);
    }


    public function getArrayColumn($column){
        $array = array();
        foreach($this as $item){
            $array[] = $item->getData($column);
        }
        return $array;
    }


    public function setAllCount($count){
        $this->_allCount = $count;
        return $this;
    }


    protected function _getItemObject(){
        $this->beforeItemObject();
        $object = new $this->_classItem();
        $object = $this->reflectItemObject($object);
        $this->afterItemObject();
        return $object;
    }


    protected function _getBackend(){
        return new $this->_classBackend();
    }


    public function delete(){
        foreach($this as $_item){
            $_item->delete();
        }
    }


    public function __call($methodName, $args){
        $reaction = Ikantam_Lib_System_Reaction::call($methodName, $args);
        if($reaction !== null)
            return $reaction;
        return parent::__call($methodName, $args);
    }


    protected function beforeItemObject(){}
    protected function reflectItemObject($object){ return $object; }
    protected function afterItemObject(){}
}
