<?php

class Zend_View_Helper_GetPublicUrl extends Zend_View_Helper_Abstract
{
    private $lib_url = null;



    public function __construct(){
        $this->lib_url = new Ikantam_Lib_Url();
    }



    function GetPublicUrl($path = ''){
        return $this->lib_url->getPublicUrl($path);
    }
}
