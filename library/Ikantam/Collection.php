<?php

abstract class Ikantam_Collection implements IteratorAggregate, Countable
{
    /**
     * Collection items
     *
     * @var array
     */
    protected $_items = array();

    /**
     * Item object class name
     *
     * @var string
     */
    protected $_itemObjectClass = '';



    public function __construct()
    {

    }


    /**
     * Retrieve collection first item
     *
     * @return Varien_Object
     */
    public function getFirstItem()
    {
        $this->load();

        if (count($this->_items)) {
            reset($this->_items);
            return current($this->_items);
        }

        return new $this->_itemObjectClass();
    }

    /**
     * Retrieve collection last item
     *
     * @return Varien_Object
     */
    public function getLastItem()
    {
        $this->load();

        if (count($this->_items)) {
            return end($this->_items);
        }

        return new $this->_itemObjectClass();
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

}
