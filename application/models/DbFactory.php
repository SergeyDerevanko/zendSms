<?php

class Application_Model_DbFactory
{

	private static $_factory;
	private $_dbh;

	/**
	 * 
	 * @return Application_Model_DbFactory
	 */
	public static function getFactory()
	{
		if (!self::$_factory) {
			self::$_factory = new self();
		}

		return self::$_factory;
	}

	/**
	 * 
	 * @param string $subconnection
	 * @return PDO
	 */
	public function getConnection($subconnection = '')
	{
		if (!$this->_dbh) {
			$paramName = 'params' . $subconnection;
			$this->_config = Zend_Registry::get('config')->resources->db->$paramName;
			$dsn = $this->getDsn();
			$user = $this->getUsername();
			$password = $this->getPassword();
			$params = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
			try {
				$this->_dbh = new PDO($dsn, $user, $password, $params);
				$this->_dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$this->_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
				$this->logException($e); //@TODO throw new Exception with custom message
			}
		}

		return $this->_dbh;
	}

	protected function getDsn()
	{
		return sprintf('mysql:dbname=%s;host=%s', $this->_config->dbname, $this->_config->host);
	}

	protected function getUsername()
	{
		return $this->_config->username;
	}

	protected function getPassword()
	{
		return $this->_config->password;
	}

	protected function logException($exc)
	{
		//echo 'Connection failed: ' . $e->getMessage(); //@TODO change this!!!
	}

}
