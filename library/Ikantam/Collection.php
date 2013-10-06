<?php

abstract class Ikantam_Collection implements IteratorAggregate, Countable
{
    /**
     * Collection items
     *
     * @var array
     */
    protected $_items = array();


    public function __construct()
    {

    }

    /**
     * Adding item to item array
     *
     * @param   Varien_Object $item
     * @return  Ikantam_Collection
     */
    public function addItem($item)
    {
        $this->_addItem($item);
        return $this;
    }

    /**
     * Add item that has no id to collection
     *
     * @param Varien_Object $item
     * @return Ikantam_Collection
     */
    protected function _addItem($item)
    {
        $this->_items[] = $item;
        return $this;
    }

    /**
     * Clear collection
     *
     * @return Ikantam_Collection
     */
    public function clear()
    {
        $this->_items = array();
        return $this;
    }


    public function count(){
        return count($this->_items);
    }


    /**
     * Implementation of IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_items);
    }


    public function __call($methodName, $args){
        $reaction = Ikantam_Lib_System_Reaction::call($methodName, $args);
        if($reaction !== null)
            return $reaction;

    }
}
