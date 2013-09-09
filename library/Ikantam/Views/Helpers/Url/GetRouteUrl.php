<?php

class Ikantam_View_Helper_Url_GetRouteUrl extends Zend_View_Helper_Abstract
{
    private $lib_url = null;



    public function __construct(){
        $this->lib_url = new Ikantam_Lib_Url();
    }



    public function getRouteUrl($route = 'default', $params = array()){
        return $this->lib_url->getRouteUrl($route, $params);
    }
}
