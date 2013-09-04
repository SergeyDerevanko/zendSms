<?php

/**
 * Person title helper
 *
 */
class Zend_View_Helper_GetActionName extends Zend_View_Helper_Abstract
{
    public function getActionName()
    {
        return Zend_Controller_Front::getInstance()->getRequest()->getActionName();
    }
}
