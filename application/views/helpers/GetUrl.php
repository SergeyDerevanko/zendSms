<?php

class Zend_View_Helper_GetUrl extends Zend_View_Helper_Abstract
{
    private $lib_url = null;



    public function __construct(){
        $this->lib_url = new Ssersh_Lib_Url();
    }



	public function getUrl($path = '', $params = array(), $reset = true, $configPath = true){
        return $this->lib_url->getUrl($path, $params, $reset, $configPath);
	}
}
