<?php

/**
 * Person title helper
 *
 */
class Ikantam_View_Helper_Mca_GetActionName extends Zend_View_Helper_Abstract
{
    public function getActionName()
    {
        return Zend_Controller_Front::getInstance()->getRequest()->getActionName();
    }
}
