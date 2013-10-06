<?php
class Ikantam_View_Helper_Option_GetObjectOptions extends Zend_View_Helper_Abstract
{
    public function getObjectOptions($type){
        return Ikantam_Option::getObjectOptions($type);
    }
}
