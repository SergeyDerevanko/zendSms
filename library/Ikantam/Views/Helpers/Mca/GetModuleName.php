<?php

/**
 * Person title helper
 *
 */
class Ikantam_View_Helper_Mca_GetModuleName extends Zend_View_Helper_Abstract
{
    public function getModuleName()
    {
        return Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
    }
}
