<?php
class Ikantam_View_Helper_Option_GetOption extends Zend_View_Helper_Abstract
{
    public function getOption($type, $name, $value = ''){
        return Ikantam_Option::getOption($type, $name, $value);
    }
}
