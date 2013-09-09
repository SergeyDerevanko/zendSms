<?php

/**
 * Person title helper
 *
 */
class Ikantam_View_Helper_Mca_GetControllerName extends Zend_View_Helper_Abstract
{
    public function getControllerName()
    {
        return Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
    }
}
