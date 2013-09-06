<?php

abstract class Ikantam_Model_Collections_Abstract extends Ikantam_Collection
{

	protected $_classBackend = null;
    protected $_classItem = null;

	public function __construct(){
        $className = explode('_', get_class($this));

        $this->_classBackend =  $className[0] . '_Model_Backend_' . end($className);
        $this->_classItem = $className[0] . '_Model_' . end($className);

		parent::__construct();
	}


    public function addData($data){
        foreach($data as $value){
            $item = $this->_getItemObject();
            $item->addData($value);
            $this->addItem($item);
        }
    }


    public function getAll(){
        $this->_getBackend()->getAll($this);
        return $this;
    }


    protected function _getItemObject(){
        return new $this->_classItem();
    }


    protected function _getBackend(){
        return new $this->_classBackend();
    }

}
