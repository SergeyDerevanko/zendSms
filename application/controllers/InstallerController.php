<?php

class InstallerController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout->setLayout('installer');
    }


    public function indexAction()
    {
        print 'installer';
           exit;

    }


    public function modulesAction(){
        $explorer = new Ikantam_Lib_Installer();

        $moduleName = $this->getParam('module_name', false);
        if($moduleName){
            $explorer->createModule($moduleName);
        }
        $this->view->modules = $explorer->getListModules();
    }


    public function moduleAction(){
        $moduleName = $this->getParam('name');

        $explorer = new Ikantam_Lib_Installer();

        $controllerName = $this->getParam('controller_name', false);

        if($controllerName){
            $explorer->createController($moduleName, $controllerName);
        }

        $modelName = $this->getParam('model_name', false);
        if($modelName){
            $this->createModel($moduleName, $modelName);
        }

        $this->view->controllers = $explorer->getListController($moduleName);
        $this->view->models = $explorer->getListModels($moduleName);
    }






	
}





