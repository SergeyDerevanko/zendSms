<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initRoutes(){
        $router = Zend_Controller_Front::getInstance()->getRouter();

        $router->addConfig(new Zend_Config_Xml( APPLICATION_PATH.'/configs/routes.xml', 'routes'));

        try {
            $routers = new Ikantam_Model_Collections_Router();
            $routers->getAll();
            foreach($routers as $_router){
                $r = new Zend_Controller_Router_Route(
                    $_router->getUrl(),
                    array(
                        'module' => $_router->getModule(),
                        'controller' => $_router->getController(),
                        'action' => $_router->getAction()
                    )
                );
                $router->addRoute($_router->getName(), $r );
            }

        } catch (Exception $e) {

        }

    }


    protected function _initView() {
        $view = new Zend_View();
        $view->addHelperPath(APPLICATION_PATH . "/../library/Ikantam/Views/Helpers/Url", "Ikantam_View_Helper_Url");

        try {
            $helpers = new Ikantam_Model_Collections_Helper();
            $helpers->getByType('view');
            foreach($helpers as $_helper){
                $view->addHelperPath(APPLICATION_PATH . $_helper->getPath(), $_helper->getName());
            }
        } catch (Exception $e) {

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
        try {
            $helpers = new Ikantam_Model_Collections_Helper();
            $helpers->getByType('controller');
            foreach($helpers as $_helper){
                Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . $_helper->getPath(), $_helper->getName());
            }
        } catch (Exception $e) {

        }
    }



}

