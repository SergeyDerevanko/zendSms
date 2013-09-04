<?php

class Application_Model_Abstract_Collection_Backend
{

	protected $_table;
	protected $_itemClass;

	protected function _getTable()
	{
		return $this->_table;
	}

	protected function _getConnection()
	{
		return Application_Model_DbFactory::getFactory()->getConnection();
	}

	public function getAll(\Application_Model_Abstract_Collection $collection)
	{
		$sql = 'SELECT * FROM ' . $this->_getTable();
		$stmt = $this->_getConnection()->prepare($sql);
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

}
