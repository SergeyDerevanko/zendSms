<?php

class Ikantam_Object
{
    /**
     * Object attributes
     *
     * @var array
     */
	protected $_data = array();
	
	/**
     * Setter/Getter underscore transformation cache
     *
     * @var array
     */
    protected static $_underscoreCache = array();
    
	/**
     * Add data to the object.
     *
     * Retains previous data in the object.
     *
     * @param  array $arr
     * @return \Application_Model_Abstract
     */
    public function addData(array $data)
    {
        foreach ($data as $index => $value) {
            $this->setData($index, $value);
        }
        return $this;
    }

    /**
     * Overwrite data in the object.
     *
     * $key can be string or array.
     * If $key is string, the attribute value will be overwritten by $value
     * If $key is an array, it will overwrite all the data in the object.
     *
     * @param  string|array $key
     * @param  mixed $value
     * @return \Application_Model_Abstract
     */
    public function setData($key, $value = null)
    {
        if (is_array($key)) {
            $this->_data = $key;
        } else {
            $this->_data[$key] = $value;
        }
        return $this;
    }
    
    /**
     * Retrieves data from the object
     *
     * If $key is empty will return all the data as an array
     * Otherwise it will return value of the attribute specified by $key
     *
     *
     * @param string $key
     * @return mixed
     */
    public function &getData($key = '', $default = null)
    {
        if ($key === '') {
            return $this->_data;
        }

        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        }
        return $default;
    }

    /**
     * Get value from _data array without parse key
     *
     * @param   string $key
     * @return  mixed
     */
    protected function _getData($key)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }
    
    
    /**
     * Set/Get attribute wrapper
     *
     * @param   string $method
     * @param   array $args
     * @return  mixed
     */
    public function __call($method, $args)
    {
        $reaction = Ikantam_Lib_System_Reaction::call($method, $args);
        if($reaction !== null)
            return $reaction;

        switch (substr($method, 0, 3)) {
            case 'get' :
                $key  = $this->_underscore(substr($method, 3));
                $data = $this->getData($key, isset($args[0]) ? $args[0] : null);
                return $data;

            case 'set' :
                $key    = $this->_underscore(substr($method, 3));
                $result = $this->setData($key, isset($args[0]) ? $args[0] : null);

                return $result;
            
            case 'uns' :
                $key    = $this->_underscore(substr($method, 3));
                if($result = key_exists($key,  $this->_data)) unset($this->_data[$key]);
                return $this;            
        }
        throw new Exception("Invalid method " . get_class($this) . "::" . $method . "(" . print_r($args, 1) . ")");
    }
    
    /**
     * Converts field names for setters and geters
     *
     * $this->setMyField($value) === $this->setData('my_field', $value)
     * Uses cache to eliminate unneccessary preg_replace
     *
     * @param  string $name
     * @return string
     */
    protected function _underscore($name)
    {
        if (isset(self::$_underscoreCache[$name])) {
            return self::$_underscoreCache[$name];
        }
        $result = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
        self::$_underscoreCache[$name] = $result;
        return $result;
    }
    
	/** Check if object record exists     
     * @return bool
     */    
    public function isExists ()
    {
        return (bool) $this->getId();
    }
}
