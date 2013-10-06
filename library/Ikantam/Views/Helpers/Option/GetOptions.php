<?php
class Ikantam_View_Helper_Option_GetOptions extends Zend_View_Helper_Abstract
{
    public function getOptions($type){
        return Ikantam_Option::getOptions($type);
    }
}
