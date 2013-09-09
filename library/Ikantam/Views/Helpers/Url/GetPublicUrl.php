<?php


class Ikantam_View_Helper_Url_GetPublicUrl extends Zend_View_Helper_Abstract
{
    private $lib_url = null;



    public function __construct(){

        $this->lib_url = new Ikantam_Lib_Url();
    }



    function getPublicUrl($path = ''){
        return $this->lib_url->getPublicUrl($path);
    }
}
