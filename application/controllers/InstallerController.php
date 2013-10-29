<?php

class InstallerController extends Ikantam_Controller_Front
{
    protected $_installer;

    public function init()
    {
        $this->_helper->layout->setLayout('installer');
        $this->_installer = new Ikantam_Lib_Installer();
        $this->view->menu = array(
            'modules' => $this->getUrl('installer/modules'),
            'db' => $this->getUrl('installer/db')
        );
    }


    public function indexAction()
    {
        print 'installer';
           exit;
    }


    public function modulesAction(){
        $explorer = $this->_installer->factory('StructGen');

        $moduleName = $this->getParam('module_name', false);
        if($moduleName){
            $explorer->createModule($moduleName);
        }
        $this->view->modules = $explorer->getListModules();
    }


    public function moduleAction(){
        $moduleName = ucfirst($this->getParam('name'));

        $explorer = $this->_installer->factory('StructGen');

        $controllerName = $this->getParam('controller_name', false);

        if($controllerName){
            $explorer->createController($moduleName, $controllerName);
        }

        $modelName = $this->getParam('model_name', false);

        if($modelName){
            $explorer->createModel($moduleName, $modelName);
        }

        $this->view->controllers = $explorer->getListController($moduleName);
        $this->view->models = $explorer->getListModels($moduleName);
    }


    public function dbAction(){
        $configName = 'default';
        $explorer = $this->_installer->factory('Db');
        $mess = false;

        if($this->getRequest()->isPost()){
            if($this->getParam('test')){
                $mess = $explorer->testConnect();
            } elseif($this->getParam('mrg')){
                $explorer->mrg();
                new Ikantam_Model_Helper('view', 'ikantam', '/../library/Ikantam/Views/Helpers/Url/GetPublicUrl', 'Ikantam_View_Helper_Url_GetPublicUrl');
                new Ikantam_Model_Helper('view', 'ikantam', '/../library/Ikantam/Views/Helpers/Url/GetRouteUrl', 'Ikantam_View_Helper_Url_GetRouteUrl');
                new Ikantam_Model_Helper('view', 'ikantam', '/../library/Ikantam/Views/Helpers/Url/GetUrl', 'Ikantam_View_Helper_Url_GetUrl');

                new Ikantam_Model_Helper('view', 'ikantam', '/../library/Ikantam/Views/Helpers/Mca', 'Ikantam_View_Helper_Mca');
                new Ikantam_Model_Helper('view', 'ikantam', '/../library/Ikantam/Views/Helpers/Option', 'Ikantam_View_Helper_Option');

            } else {
                $explorer->setConfig(
                    $this->getParam('adapter', 'pdo_mysql'),
                    $this->getParam('host', ''),
                    $this->getParam('username', ''),
                    $this->getParam('password', ''),
                    $this->getParam('dbname', ''),
                    $this->getParam('prefix', ''),
                    $configName);
            }
        }

        $this->view->mess = $mess;
        $conf = $explorer->getConfig($configName);
        $this->view->dbConfig = $conf;
    }

}





