<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initRoutes(){
        Zend_Controller_Front::getInstance()
            ->getRouter()
            ->addConfig(new Zend_Config_Xml( APPLICATION_PATH.'/configs/routes.xml', 'routes'));

    }


    protected function _initViewHelpers() {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        if (null === $viewRenderer->view) {
            $viewRenderer->initView();
        }
    }


    protected function _initConfig(){
        $config = new Zend_Config($this->getOptions(), true);
        Zend_Registry::set('config', $config);
        return $config;
    }



    /*protected function _init_Db()
    {
        $resource = $this->getPluginResource('db');
        Zend_Registry::set('db', $resource->getDbAdapter());
    }


    
    protected function _initActionHelpers()
    { 
        Zend_Controller_Action_HelperBroker::addPath(
            'Ikantam/Controller/Action/Helper/',
            'Ikantam_Controller_Action_Helper');
    }*/

}

