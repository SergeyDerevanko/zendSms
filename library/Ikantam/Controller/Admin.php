<?php

abstract class Ikantam_Controller_Admin extends Ikantam_Controller_Front
{

	public function __construct(\Zend_Controller_Request_Abstract $request, \Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
	{
		parent::__construct($request, $response, $invokeArgs);
	}



}
