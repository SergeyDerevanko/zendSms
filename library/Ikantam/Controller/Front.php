<?php

abstract class Ikantam_Controller_Front extends Zend_Controller_Action
{
    protected $_styles = array();
    protected $_scripts = array();

	public function __construct(\Zend_Controller_Request_Abstract $request, \Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
	{
		parent::__construct($request, $response, $invokeArgs);

        $this->_styles = new Zend_Config_Xml( APPLICATION_PATH.'/configs/head.xml', 'styles');
        $this->_scripts = new Zend_Config_Xml( APPLICATION_PATH.'/configs/head.xml', 'scripts');
	}


    public function dispatch($action){
        parent::dispatch($action);

        $layoutName = $this->_helper->layout->getLayout();

        if(!empty($this->_styles->url))
        foreach($this->_styles->url as $_style){
            if(array_intersect(array('all', $layoutName), explode(',', $_style->layout)))
                $this->addStylePublic($_style->url);
        }

        if(!empty($this->_scripts->_url))
        foreach($this->_scripts->url as $_script){
            if(array_intersect(array('all', $layoutName), explode(',', $_script->layout)))
                $this->addScriptPublic($_script->url);
        }
    }


    public function addScriptPublic($path){
        $this->view->headScript()->appendFile($this->getPublicUrl($path), 'text/javascript');
    }


    public function addStylePublic($path){
        $this->view->headLink()->appendStylesheet($this->getPublicUrl($path));
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


    public function getObjectOption($type, $name, $value = ''){
        return Ikantam_Option::getObjectOption($type, $name, $value);
    }


    public function getObjectOptions($type){
        return Ikantam_Option::getObjectOptions($type);
    }


    public function getOption($type, $name, $value = ''){
        return Ikantam_Option::getOption($type, $name, $value);
    }


    public function getOptions($type){
        return Ikantam_Option::getOptions($type);
    }

}
