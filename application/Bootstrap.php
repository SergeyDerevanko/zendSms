<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initRoutes(){
        Zend_Controller_Front::getInstance()
            ->getRouter()
            ->addConfig(new Zend_Config_Xml( APPLICATION_PATH.'/configs/routes.xml', 'routes'));

    }


    protected function _initViewHelpers() {
        $view = new Zend_View();
        $view->addHelperPath(APPLICATION_PATH . "/../library/Ikantam/Views/Helpers/Url", 'Ikantam_View_Helper_Url');
        $view->addHelperPath(APPLICATION_PATH . "/../library/Ikantam/Views/Helpers/Mca", 'Ikantam_View_Helper_Mca');
        $view->addHelperPath(APPLICATION_PATH . "/../library/Ikantam/Views/Helpers/Option", 'Ikantam_View_Helper_Option');

        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
    }


    protected function _initConfig(){
        $config = new Zend_Config($this->getOptions(), true);
        Zend_Registry::set('config', $config);
        return $config;
    }



    protected function _initDb(){
        Ikantam_Model::getConnect();
    }

      /*
    
    protected function _initActionHelpers()
    { 
        Zend_Controller_Action_HelperBroker::addPath(
            'Ikantam/Controller/Action/Helper/',
            'Ikantam_Controller_Action_Helper');
    }*/




}

