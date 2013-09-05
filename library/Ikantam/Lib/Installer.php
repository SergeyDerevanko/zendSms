<?php

class Ikantam_Lib_Installer
{
    private $_location;
    private $_templatesPath;

    function __construct(){
        $this->_location = new Ikantam_Lib_Explorer();
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


    public function createModule($moduleName){
        $dir = new Ikantam_Lib_Explorer_Dir($this->getPath() . '/modules');

        $moduleName  = strtolower($moduleName);
        $moduleNameB = ucfirst($moduleName);

        $dirModule = $dir->createDir($moduleName);

        $dirModule->createDir('controllers');

        $dirModels = $dirModule->createDir('models');
        $dirModels->createDir('Collections');
        $dirModels->createDir('Backend');

        $dirView = $dirModule->createDir('views');
        $dirView->createDir('scripts');
        $dirView->createDir('helpers');

        $this->createController($moduleNameB, 'Index');
    }



    public function createController($moduleName, $controllerName){
        $moduleNameB = ucfirst($moduleName);
        $controllerNameB = ucfirst($controllerName);

        $tmp = new Ikantam_Lib_Explorer_File($this->_templatesPath . '\controller.phtml');
        $tmp = $tmp->ready();
        $tmp = str_replace('{$moduleNameB}', $moduleNameB, $tmp);
        $tmp = str_replace('{$controllerNameB}', $controllerNameB, $tmp);

        $dir = new Ikantam_Lib_Explorer_Dir($this->getPath() . '/modules/' . $moduleName . '/controllers');


        $dir->createFile(ucfirst($controllerName) . 'Controller.php')
            ->put($tmp);
    }

    public function createModel($moduleName, $modelName){
        $explorer = new Ikantam_Lib_Explorer();
        $models = $explorer->getLocationDir('application/modules/' . strtolower($moduleName) . '/models');
        $models->createFile($modelName . '.php')->put('
<?php
    class ' . ucfirst($moduleName) . '_Model_' . $modelName . '  extends Application_Model_Abstract{

    protected function _getBackend(){
        return new ' . ucfirst($moduleName) . '_Model_Backend_' . $modelName . '();
    }
}
        ');


        $models->cdNew('Backend')->createFile($modelName . '.php')->put('
<?php

class ' . ucfirst($moduleName) . '_Model_Backend_' . $modelName . ' extends Application_Model_Backend_Abstract
{

}
        ');


        $models->cdNew('Collections')->createFile($modelName . '.php')->put('
<?php

class ' . ucfirst($moduleName) . '_Model_Collections_' . $modelName . ' extends Application_Model_Collections_Abstract
{
    protected function _getItemObject(){
        return new ' . ucfirst($moduleName) . '_Model_' . $modelName . '();
    }


    protected function _getBackend(){
        return new ' . ucfirst($moduleName) . '_Model_Backend_' . $modelName . '();
    }
}
        ');
    }


    public function getLocationDir($cmd = null){
        $object = new Ikantam_Lib_Explorer_Dir($this->_path);
        if($cmd){
            $object->cd($cmd);
        }
        return $object;
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
