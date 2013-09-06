<?php

abstract class Ikantam_Controller_Front extends Zend_Controller_Action
{

	public function __construct(\Zend_Controller_Request_Abstract $request, \Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
	{
		parent::__construct($request, $response, $invokeArgs);
	}


    public function getUrl($path = '', $params = array(), $reset = true){
        return Ikantam_Lib_Url::getUrl($path, $params, $reset);
    }


    public function getPublicUrl($path = ''){
        return Ikantam_Lib_Url::getPublicUrl($path);
    }


    public function getRouteUrl($routName, $params = array()){
        return Ikantam_Lib_Url::getRouteUrl($routName, $params);
    }
}
