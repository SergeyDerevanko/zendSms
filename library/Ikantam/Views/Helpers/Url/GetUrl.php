<?php

class Ikantam_View_Helper_Url_GetUrl extends Zend_View_Helper_Abstract
{
    private $lib_url = null;



    public function __construct(){
        $this->lib_url = new Ikantam_Lib_Url();
    }



	public function getUrl($path = '', $params = array(), $reset = true, $configPath = true){
        return $this->lib_url->getUrl($path, $params, $reset, $configPath);
	}
}
