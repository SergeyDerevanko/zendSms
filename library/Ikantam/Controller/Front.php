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
        if(!empty($this->_styles->url))   {

            if(empty($this->_styles->url->layout)){
                $urls = $this->_styles->url;
            } else {
                $urls = array($this->_styles->url);
            }

            foreach($urls as $_style){
                if(array_intersect(array('all', $layoutName),
                    explode(',', $_style->layout)))
                    if(!empty($_style->url)){
                        $this->addStylePublic($_style->url);
                    } else {
                        $this->addStyle($_style->href);
                    }
            }
        }

        if(!empty($this->_scripts->url))  {
            if(empty($this->_scripts->url->layout)){
                $urls = $this->_scripts->url;
            } else {
                $urls = array($this->_scripts->url);
            }
            foreach($urls as $_script){
                if(array_intersect(array('all', $layoutName),
                    explode(',', $_script->layout)))
                    if(!empty($_script->url)){
                        $this->addScriptPublic($_script->url);
                    } else {
                        $this->addScript($_script->href);
                    }
            }
        }
    }



    public function __call($methodName, $args){
        $reaction = Ikantam_Lib_System_Reaction::call($methodName, $args);
        if($reaction !== null)
            return $reaction;
        return parent::__call($methodName, $args);
    }



    public function addScriptPublic($path){
        $this->addScript($this->getPublicUrl($path));
    }


    public function addScript($url){
        $this->view->headScript()->appendFile($url, 'text/javascript');
    }


    public function addStylePublic($path){
        $extension = explode("/", $path);
        $extension = explode(".", end($extension) );
        $extension = end($extension);
        if($extension == 'less'){
            $_path = substr($path, 0, -strlen($extension)) . 'css';
            $modTime = filemtime($path);
            if($modTime > filemtime($_path)){
                $less = new Ikantam_Lib_lessc();
                $less->checkedCompile($path, $_path);
            }
            $path = $_path . '?' . $modTime;
        }
        $this->addStyle($this->getPublicUrl($path));
    }


    public function addStyle($url){
        $this->view->headLink()->appendStylesheet($url);
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
