<?php
class Ikantam_View_Helper_Option_GetObjectOption extends Zend_View_Helper_Abstract
{
    public function getObjectOption($type, $name, $value = ''){
        return Ikantam_Option::getObjectOption($type, $name, $value);
    }
}
