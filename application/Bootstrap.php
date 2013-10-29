<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initRoutes(){
        Zend_Controller_Front::getInstance()
            ->getRouter()
            ->addConfig(new Zend_Config_Xml( APPLICATION_PATH.'/configs/routes.xml', 'routes'));

    }


    protected function _initView() {
        $view = new Zend_View();

        $helpers = new Ikantam_Model_Collections_Helper();
        $helpers->getByType('view');
        foreach($helpers as $_helper){
            $view->addHelperPath(APPLICATION_PATH . $_helper->getPath(), $_helper->getName());
        }

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper( 'ViewRenderer' );
        $viewRenderer->setView($view);
        return $view;
    }


    protected function _initConfig(){
        $config = new Zend_Config($this->getOptions(), true);
        Zend_Registry::set('config', $config);
        return $config;
    }



    protected function _initDb(){
        Ikantam_Model::getConnect();
    }


    protected function _initControllerHelpers()
    {
        $helpers = new Ikantam_Model_Collections_Helper();
        $helpers->getByType('controller');
        foreach($helpers as $_helper){
            Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . $_helper->getPath(), $_helper->getName());
        }
    }



}

