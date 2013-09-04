<?php

abstract class Application_Model_Abstract_Collection extends Ikantam_Collection
{

	protected $_backendClass;
	protected $_backend;
	protected $_filterAdapter;
	protected $_filterClass;
    
    private $_sortColumnName_  =  null;

	public function __construct()
	{
		$this->_backendClass = str_replace('_Collection', '_Backend', get_class($this));
		
		if (!$this->_filterClass) {
			$this->_filterClass = $this->_backendClass;
		}
		parent::__construct();
	}

	protected function _getBackend()
	{
		if (!$this->_backend) {
			$this->_backend = new $this->_backendClass();
		}
		return $this->_backend;
	}
	
	protected function getFilterAdapter()
	{
		if (!$this->_filterAdapter) {
			$this->_filterAdapter = new $this->_filterClass();
		}
		return $this->_filterAdapter;
	}
	
	public function _addFilter($field, $value)
	{
		$this->getFilterAdapter()->addFilter($field, $value);
		return $this;
	}
	
	public function _addFilterRange($field, $minValue, $maxValue)
	{
		$this->getFilterAdapter()->addFilterRange($field, $minValue, $maxValue);
		return $this;
	}

	public function _setLimit($offset, $limit)
	{
		$this->getFilterAdapter()->setLimit($offset, $limit);
		return $this;
	}
	
	public function setSort($field, $mode)
	{
		$this->getFilterAdapter()->setSort($field, $mode);
		return $this;
	}

	public function load2()
	{
		$this->getFilterAdapter()->load2($this, $this->_getBackend());
		return $this;
	}
    
    public function getAll_ ($limit = null)
    {
        $this->_getbackend()->getAll_($this, $limit);
        return $this;
    }
//Sort  
    private final function _sort_by_desc_($a, $b) {
        $method = $this->_sortColumnName_;
        if ($a->$method() == $b->$method()) {
            return 0;
        }
        return ($a->$method() > $b->$method()) ? -1 : 1;        
    }
    
    private final function _sort_by_asc_($a, $b) {
        $method = $this->_sortColumnName_;
        if ($a->$method() == $b->$method()) {
            return 0;
        }
        return ($a->$method() > $b->$method()) ? 1 : -1;        
    }      

	/** Sort collection items by column values
     * @param string $column - Column name for sort compare
     * @param string $direction - sort direction (ASC, DESC)
     * @param bool $saveKeys - use original keys if true
     * @return self 
     */      
    public function sortByColumn ($column, $direction = 'ASC', $saveKeys = true)
    {
        $sort = $saveKeys ? 'uasort' : 'usort';
        $this->_sortColumnName_ = 'get'.ucfirst($column);
        if(trim(strtoupper($direction) == 'ASC')) {
            $sort($this->_items, array($this, '_sort_by_asc_'));
            }elseif(trim(strtoupper($direction)) == 'DESC') {
                 $sort($this->_items, array($this, '_sort_by_desc_'));  
            } else {
                throw new Exception('Unknown sort direction "'. $direction .'"');
            }
        return $this;
    }
//*********

    /** Retreives  matched data from DB where field name is part after getBy_ and condition value is first argument.
     * getBy_table_field_name
     *
     * @param   string $method
     * @param   array $args
     * @return  mixed
     */
    public function __call($method, $args)
    {
        if (substr($method, 0, 6) == 'getBy_') {
            $field = substr($method, 6, strlen($method));
            $this->_getbackend()->getCollectionByFieldValue__($this, $field, $args[0]);
            return $this;
        } else {

        throw new Exception("Invalid method " . get_class($this) . "::" . $method . "(" . print_r($args, 1) . ")");            
                
        }
    }


}
