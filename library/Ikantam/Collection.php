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

    /**
     * Total items number
     *
     * @var int
     */
    protected $_totalRecords;

    public function __construct()
    {

    }

    /**
     * Retrieve collection all items count
     *
     * @return int
     */
    public function getSize()
    {
		$this->load();
        
        return count($this->_items);
        
        if (is_null($this->_totalRecords)) {
            $this->_totalRecords = count($this->getItems());
        }
        return intval($this->_totalRecords);
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
     * Retrieve collection items
     *
     * @return array
     */
    public function getItems()
    {
        $this->load();
        return $this->_items;
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

    /**
     * Retrieve collection empty item
     *
     * @return Varien_Object
     */
    public function getNewEmptyItem()
    {
        return new $this->_itemObjectClass();
    }

    /**
     * Load data
     *
     * @return  Ikantam_Collection
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
        return $this;
    }

    /**
     * Load data
     *
     * @return  Ikantam_Collection
     */
    public function load($printQuery = false, $logQuery = false)
    {
        return $this->loadData($printQuery, $logQuery);
    }

    /**
     * Implementation of IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        $this->load();
        return new ArrayIterator($this->_items);
    }

    /**
     * Retrieve count of collection loaded items
     *
     * @return int
     */
    public function count()
    {
        $this->load();
        return count($this->_items);
    }
    
    	/**
         * Generates an array from objects field.
         * @param string $column 
         * @return array
         */
    public function getColumn ($column)
    {
        $result = array();
        $column = 'get'.ucfirst($column);
        foreach($this->_items as $object)
        { 
            $result[] = $object->$column();
        }
        return $result;
    }
    
	/**
     * Generates an array from objects fields.
     * @param array $columns 
     * @return array
     */
    public function getColumns (array $columns)
    {
        $result = array();
        $methods = array();
        foreach($columns as $key => $column)
        {
            $methods[$key] = 'get'.ucfirst($column);
        }
        
        foreach($this->_items as $object)
        { 
           $result[] = array(); 
            foreach($methods as $key => $method)
            {
                $result[count($result) - 1][$columns[$key]] = $object->$method();
            }
        }
        return $result;        
    }
    
	/**
     * @param string $column
     * @param mixed  $value 
     * @param bool   $createIfNotExist
     * @return self
     */    
    public function fillColumn ($column, $value, $createIfNotExist = false)
    {
        if($createIfNotExist)
        {
            foreach($this->_items as $object)
            {
                $data = $object->getData();
                $data[$column] = $value;
                $object->setData($data);
            }
        } else
            {
                foreach($this->_items as $object)
                {
                    $data = $object->getData();
                    if(key_exists($column, $data))
                        {
                            $data[$column] = $value;
                            $object->setData($data);
                        }
                }   
            }
        return $this;
    }

<<<<<<< HEAD

	/** Merge this collection with other.  This method does not change original collection!
     * 
     * @param Ikantam_Collection $collection
     * return Ikantam_Collection
     */    
    public function merge (\Ikantam_Collection $collection)
    {
        $providedItems = $collection->getItems();        
        $myIds = $this->getColumn('id');
        
        $newCollection = clone($this);      
        
        foreach($providedItems as $item)
        {
            if(!in_array($item->getId(-1), $myIds))
            {
                $newCollection->addItem($item);
            }   
        }       
       return $newCollection;
    }
    

	/**
     * Removes item with duplicate id 
     * @return self 
     */    
    public function removeDuplicateId ()
    {
        $ids = array_unique($this->getColumn('id'));
        if(!$ids) {
            return $this;
        }
        $ids = array_combine($ids, $ids);

        $aux = array();
        
        foreach($this->_items as $item) {
            if(in_array($id = $item->getId(), $ids)) {
                $aux[] = $item;
                unset($ids[$id]);
            }
        }
        
        $this->_items = $aux;
        
        return $this;
        
    }

=======
>>>>>>> aad719df3ef0b855639e46632dbfd286be79e60e
}
