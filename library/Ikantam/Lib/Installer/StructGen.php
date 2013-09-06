<?php

class Ikantam_Lib_Installer_StructGen
{
    private $_templatesPath;

    function __construct(){

        $this->setPath($this->getAppPath());

        $this->_templatesPath = $this->getPath() . '/../library/Ikantam/Lib/Installer/templates';
        return $this;
    }


    public function getListModules(){
        $dir = new Ikantam_Lib_Explorer_Dir($this->getPath() . '/modules');
        return $dir->getList('dir');
    }

    public function getListController($moduleName){
        $dir = new Ikantam_Lib_Explorer_Dir($this->getPath() . '/modules/' . $moduleName . '/controllers');
        return $dir->getList('file');
    }

    public function getListModels($moduleName){
        $dir = new Ikantam_Lib_Explorer_Dir($this->getPath() . '/modules/' . $moduleName . '/models');
        return $dir->getList('file');
    }


    public function createModule($moduleNameB){
        $dir = new Ikantam_Lib_Explorer_Dir($this->getPath() . '/modules');

        $dirModule = $dir->createDir(strtolower($moduleNameB));

        $tmp = new Ikantam_Lib_Explorer_File($this->_templatesPath . '\bootstrap');
        $tmp = $tmp->ready();
        $tmp = str_replace('{$moduleNameB}', $moduleNameB, $tmp);
        $dirModule->createFile('Bootstrap.php')->put($tmp);

        $dirModule->createDir('controllers');

        $dirModels = $dirModule->createDir('models');
        $dirModels->createDir('Collections');
        $dirModels->createDir('Backend');

        $dirView = $dirModule->createDir('views');
        $dirView->createDir('scripts');
        $dirView->createDir('helpers');

        $this->createController($moduleNameB, 'Index');
    }



    public function createController($moduleNameB, $controllerNameB){

        $tmp = new Ikantam_Lib_Explorer_File($this->_templatesPath . '\controller');
        $tmp = $tmp->ready();
        $tmp = str_replace('{$moduleNameB}', $moduleNameB, $tmp);
        $tmp = str_replace('{$controllerNameB}', $controllerNameB, $tmp);

        $dir = new Ikantam_Lib_Explorer_Dir($this->getPath() . '/modules/' . strtolower($moduleNameB) . '/controllers');

        $dir->createFile(ucfirst($controllerNameB) . 'Controller.php')
            ->put($tmp);

        $view = new Ikantam_Lib_Explorer_Dir($this->getPath() . '/modules/' . strtolower($moduleNameB) . '/views/scripts');
        $view->createDir(strtolower($controllerNameB));

        $this->createView($moduleNameB, $controllerNameB, 'Index');
    }


    public function createView($moduleNameB, $controllerNameB, $nameB){
        $views = new Ikantam_Lib_Explorer_Dir($this->getPath() . '/modules/' . strtolower($moduleNameB) . '/views/scripts/' . strtolower($controllerNameB));

        $tmp = new Ikantam_Lib_Explorer_File($this->_templatesPath . '\view');
        $tmp = $tmp->ready();
        $tmp = str_replace('{$moduleNameB}', $moduleNameB, $tmp);
        $tmp = str_replace('{$controllerNameB}', $controllerNameB, $tmp);
        $tmp = str_replace('{$nameB}', $nameB, $tmp);

        $views->createFile(strtolower($nameB) . '.phtml')
            ->put($tmp);
    }


    public function createModel($moduleNameB, $modelNameB){
        $models = new Ikantam_Lib_Explorer_Dir($this->getPath() . '/modules/' . strtolower($moduleNameB) . '/models');

        $tmp = new Ikantam_Lib_Explorer_File($this->_templatesPath . '\model');
        $tmp = $tmp->ready();
        $tmp = str_replace('{$moduleNameB}', $moduleNameB, $tmp);
        $tmp = str_replace('{$modelNameB}', $modelNameB, $tmp);
        $models->createFile($modelNameB . '.php')->put($tmp);

        $tmp = new Ikantam_Lib_Explorer_File($this->_templatesPath . '\backend');
        $tmp = $tmp->ready();
        $tmp = str_replace('{$moduleNameB}', $moduleNameB, $tmp);
        $tmp = str_replace('{$modelNameB}', $modelNameB, $tmp);
        $models->cdNew('Backend')->createFile($modelNameB . '.php')->put($tmp);

        $tmp = new Ikantam_Lib_Explorer_File($this->_templatesPath . '\collection');
        $tmp = $tmp->ready();
        $tmp = str_replace('{$moduleNameB}', $moduleNameB, $tmp);
        $tmp = str_replace('{$modelNameB}', $modelNameB, $tmp);
        $models->cdNew('Collections')->createFile($modelNameB . '.php')->put($tmp);
    }



    public function getAppPath(){
        return APPLICATION_PATH;
    }


    public function setPath($path = ''){
        if(!empty($path)){
            $this->_path = $path;
        }
        return $this;
    }


    public function getPath(){
        return $this->_path;
    }




}
