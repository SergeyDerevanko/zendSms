<?php

/**
 * Person title helper
 *
 */
class Zend_View_Helper_GetControllerName extends Zend_View_Helper_Abstract
{
    public function getControllerName()
    {
        return Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
    }
}
