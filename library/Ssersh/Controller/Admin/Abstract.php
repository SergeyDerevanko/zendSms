<?php

abstract class Ssersh_Controller_Admin_Abstract extends Zend_Controller_Action
{

	public function __construct(\Zend_Controller_Request_Abstract $request, \Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
	{
		parent::__construct($request, $response, $invokeArgs);
	}



}
