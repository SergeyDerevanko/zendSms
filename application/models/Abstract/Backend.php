<?php

abstract class Application_Model_Abstract_Backend 
{

	protected $_table;
	protected $_itemClass;
    protected $_collectionClass;
    
    
	/**
     * Full info about table structure for each model
     * structure ('modelClassName' => fields)
     * @type array
     */    
    private static  $__describedFields ; 
    
  /** Discribes table field names
   * @type array
   * @example
   * in object backend: 
   * protected $_fields_ = array('id', 'name', 'age', 'phone');
   * ...
   * protected function _insert(\Application_Model_Abstract $object) {
   *    $this->runStandartInsert(array('name', 'age', 'phone'), $object);
   * } 
   */
    protected $_fields_ = array();
    
	public function __construct()
	{
		$this->_itemClass = str_replace('_Backend', '', get_class($this));
        $this->_collectionClass = $this->_itemClass . '_Collection';
        
        $this->__init__();
	}
    
    private final function __init__() {
        $this->__describeTable();
        $this->_init();
    }
    
	/**
     * Describes table fields and fill $_fields_ by field names if it not specified manually 
     * @return array 
     */    
    private function __describeTable() {
        $key = get_class($this);
        
        if(!isset(self::$__describedFields[$key])) {            
            $sql = "DESCRIBE ".$this->_getTable();
            $stmt = $this->__prepareSql($sql);            
            $stmt->execute(); 
            
        $rawDescibe = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        $row_defaults = array(
            'Length'          => null,
            'Unsigned'        => null,
            'Primary'         => false,
            'PrimaryPosition' => null,
            'Identity'        => false,
            'Values'          => array()  
        ); 
       
       foreach ($rawDescibe as $row) {
        
           $resultRow = array_merge($row_defaults, $row); 
           if (strpos($row['Type'], 'unsigned') !== false) {
                $resultRow['Unsigned'] = true;
           }           
           preg_match('/^\w*/', $row['Type'], $mathces);
           $resultRow['Type'] = $mathces[0];
           
           $resultRow['Null'] = ($row['Null'] == 'YES') ? true : false;
           
            if(in_array($resultRow['Type'], array('enum', 'set')) ) {
                preg_match_all("/'(\w*)'/", $row['Type'], $matches);
                $resultRow['Values'] = $matches[1];
            }
            else if (preg_match('/^((?:var)?char)\((\d+)\)/', $row['Type'], $matches)) {
                $resultRow['Type'] = $matches[1];
                $resultRow['Length'] = $matches[2];
              }         
            else if (preg_match('/^decimal\((\d+),(\d+)\)/', $row['Type'], $matches)) {
                $resultRow['Type'] = 'decimal';
                $resultRow['Precision'] = $matches[1];
                $resultRow['Scale'] = $matches[2];
            } else if (preg_match('/^float\((\d+),(\d+)\)/', $row['Type'], $matches)) {
                $resultRow['Type'] = 'float';
                $resultRow['Precision'] = $matches[1];
                $resultRow['Scale'] = $matches[2];
            } else if (preg_match('/^((?:big|medium|small|tiny)?int)\((\d+)\)/', $row['Type'], $matches)) {
                $resultRow['Type'] = $matches[1];
                /**
                 * The optional argument of a MySQL int type is not precision
                 * or length; it is only a hint for display width.
                 */
            }
            
            if (strtoupper($row['Key']) == 'PRI') {
                $resultRow['Primary'] = true;
                if ($resultRow['Extra'] == 'auto_increment') {
                    $resultRow['Identity'] = true;
                } else {
                    $row['Identity'] = false;
                }
            }
            
            self::$__describedFields[$key][] = $resultRow;           
       }            
            
            
                       
            
        }        

        
        if(!$this->_fields_) {
            foreach(self::$__describedFields[$key] as $index => $field) {
                $this->_fields_[] = $field['Field'];    
            }
        }
        
        return self::$__describedFields[$key];        
        
    }
    
	/**
     * Prepare sql
     * @param string $sql  
     * @return PDO statement
     */    
    protected function __prepareSql($sql) {
        return $this->_getConnection()->prepare($sql);
    }
    
    public function getDescribedFields ()
    {  
      return $this->__describeTable();
    }
    
    public function getFieldNames ()
    {
        return $this->_fields_;
    } 
    
    protected function _init() {}

	public function _getTable()
	{
		return $this->_table;
	}

	protected function _getConnection()
	{
		return Application_Model_DbFactory::getFactory()->getConnection();
	}

	public function getById(\Application_Model_Abstract $object, $id)
	{
		$sql = "SELECT * FROM `" . $this->_getTable() . "` WHERE `id` = :id";
		$stmt = $this->_getConnection()->prepare($sql);

		$stmt->bindParam(':id', $id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($row) {
			$object->addData($row);
		}
	}
	
	public function filter($collection)
	{
		$where = array();
	
		foreach ($collection->getFilters() as $field => $values) {
			$where[] = '`'.$field.'` in ("'.implode('", "', $values).'")';
		}
		
		//$sql = "SELECT * FROM `" . $this->_getTable() . "` WHERE :where";
		$sql = "SELECT * FROM `" . $this->_getTable() . "` WHERE " . implode(' AND ', $where);
		$stmt = $this->_getConnection()->prepare($sql);
		//$stmt->bindParam(':where', $where);

		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		if ($rows) {
			foreach ($rows as $row) {
				$item = new $this->_itemClass();
				$item->addData($row);
				$collection->addItem($item);
			}
		}
	}

	public function save(\Application_Model_Abstract $object)
	{
		if ($object->getIsDeleted()) {
			$this->_delete($object);
		} elseif ($object->getId()) {
			$this->_update($object);
		} else {
			$this->_insert($object);
		}
	}

	protected function _delete(\Application_Model_Abstract $object)
	{
		$id = $object->getId();

		if (!$id) {
			return;
		}

		$sql = "DELETE FROM `" . $this->_getTable() . "` where `id` = :id;";
		$stmt = $this->_getConnection()->prepare($sql);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	}
 
	/** Fill collection from prepared PDOStatement
     * @param  PDOStatement $stmt
     * @param  Application_Model_Abstract_Collection $collection
     * @param  string $objectClass - optional
     */    
    protected function fillCollection(PDOStatement $stmt, \Application_Model_Abstract_Collection $collection, $objectClass = null)
    { $objectClass = ($objectClass) ? $objectClass : $this->_itemClass;
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(is_array($result))
        {
        foreach ($result as $row) {
            $object = new $objectClass();
            $object->addData($row);
            $collection->addItem($object);
        }             
        }         
    }
    

	/** Fill the object with first matched data.
     * @param $object  - to fill
     * @param string $field - DB table field name
     * @param string $value - to use in "WHERE" 
     */    
    public function getByFieldValue__ (\Application_Model_Abstract $object, $field, $value)
    {
        $sql = "SELECT * FROM `". $this->_getTable() ."` WHERE `".$field."` = :value";

        $stmt = $this->_getConnection()->prepare($sql);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
       if(is_array($result))
            $object->addData($result);
    }
 
 
 	/** Generates and runs sql INSERT query
      * @param array $fields - list of fields to insert
      * @param Application_Model_Abstract $object - object to provide data
      * @return bool - insert result
      */   
    public function runStandartInsert (array $fields, \Application_Model_Abstract $object)
    {
        $sql = "INSERT INTO `". $this->_getTable() ."` (";
        $valuesPart = ' VALUES (';
        $binds = array();
        foreach($fields as $field) {
            if(!in_array($field, $this->_fields_)) {
                throw new Exception(__METHOD__.' Unknown column "'. $field .'"');
            }
            $fv = '`' . $field . '`';
            $val =  ':' . $field .'_';
            $sql .= $fv . ',';
            $valuesPart .= $val . ',';

            $field = 'get' . $field;
            $binds[$val] = $object->$field();
            
        } 
        $sql = substr($sql, 0, strlen($sql) - 1) . ')';
        $valuesPart = substr($valuesPart, 0, strlen($valuesPart) - 1) . ')';

        $sql .= $valuesPart;

        $stmt = $this->_getConnection()->prepare($sql);

        foreach($binds as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $result = $stmt->execute();
        $object->setId($this->_getConnection()->lastInsertId());
        
        return $result;
                
    }
    
 	/** Generates and runs sql UPADTE query
      * @param array $fields - list of fields to insert
      * @param Application_Model_Abstract $object - object to provide data
      * @param string $where - field name in where clause, id by default.
      * @return bool - insert result
      */      
    public function runStandartUpdate (array $fields, \Application_Model_Abstract $object, $where = null)
    {
        $sql = "UPDATE `" .$this->_getTable(). "` SET ";
        $binds = array();
        if(!$where) {
            $where = " WHERE id = :id_";
            $binds[':id_'] = $object->getId();
        } else {            
            $method = 'get'.ucfirst($where);
            $binds[':'.$where.'_'] = $object->$method();
            $where = " WHERE ".$where." :".$where."_";
        }
        
        foreach($fields as $field) {
            if(!in_array($field, $this->_fields_)) {
                throw new Exception(__METHOD__.' Unknown column "'. $field .'"');
            }
            $fv = '`' . $field . '`';
            $val =  ':' . $field .'_';
            $sql .= ' '.$fv . ' = '.$val.',';

            $field = 'get' . $field;
            $binds[$val] = $object->$field();
            
        }        

        
        $sql = substr($sql, 0, strlen($sql) - 1);
        $sql .= $where;
        $stmt = $this->_getConnection()->prepare($sql);

        foreach($binds as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $result = $stmt->execute();
        
        return $result;                        
    }
    
	/** Retreive all data from table
     * @param  Application_Model_Abstract_Collection $collection
     */    
    public function getAll_ (\Application_Model_Abstract_Collection $collection, $limit = null)
    {
      $sql = "SELECT * FROM `" .$this->_getTable(). "` WHERE 1 ";
      if((int) $limit) {
        $sql .= 'LIMIT '.$limit;
      }
      $stmt = $this->_getConnection()->prepare($sql);
      
      $this->fillCollection($stmt, $collection);  
    }
    
	/**     
     * see  Application_Model_Abstract_Collection::__call
     */    
    public function getCollectionByFieldValue__ (\Application_Model_Abstract_Collection $collection, $field, $value)
    {
        if(strpos($field, '`') !== false) {
            exit;
        }
        $sql = "SELECT * FROM `". $this->_getTable() ."` WHERE `". $field ."` = :val";
        
        $stmt = $this->_getConnection()->prepare($sql);
        $stmt->bindParam(':val', $value);
        
        $this->fillCollection($stmt, $collection);       
        
    }        

	abstract protected function _insert(\Application_Model_Abstract $object);

	abstract protected function _update(\Application_Model_Abstract $object);
}
