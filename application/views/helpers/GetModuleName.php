<?php

/**
 * Person title helper
 *
 */
class Zend_View_Helper_GetModuleName extends Zend_View_Helper_Abstract
{
    public function getModuleName()
    {
        return Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
    }
}
