<?php

class Ikantam_Lib_Explorer
{

    private $_path = '';
    private $_list = null;


    function __construct($path = null){
        if(!$path) $path = '';
        $appPath = $this->getAppPath();
        $path = $appPath . '\\' . trim($path, "\\");
        $this->setPath($path);
        return $this;
    }


    public function getListModules(){
        $dir = $this->getLocationDir('application/modules');
        return $dir->getList('dir');
    }

    public function getListController($moduleName){
        $dir = $this->getLocationDir('application/modules/' . $moduleName . '/controllers');
        return $dir->getList('file');
    }

    public function getListModels($moduleName){
        $dir = $this->getLocationDir('application/modules/' . $moduleName . '/models');
        return $dir->getList('file');
    }

    public function createModule($moduleName){
        $dir = $this->getLocationDir('application/modules');
        $moduleName = strtolower($moduleName);
        $module = ucfirst($moduleName);

        $dirModule = $dir->createDir($moduleName);

        $dirModule->createDir('controllers');


        $dirModels = $dirModule->createDir('models');
        $dirModels->createDir('Collections');
        $dirModels->createDir('Backend');

        $dirView = $dirModule->createDir('views');
        $dirView->createDir('scripts');
        $dirView->createDir('helpers');

        $this->createController($module, 'Index');
    }



    public function createController($moduleName, $controller){
        $explorer = new Ikantam_Lib_Explorer();
        $module = $explorer->getLocationDir('application/modules/' . strtolower($moduleName));
        $module->cdNew('controllers')
            ->createFile(ucfirst($controller) . 'Controller.php')
            ->put('<?php class ' . ucfirst($moduleName) . '_' . ucfirst($controller) . 'Controller extends Zend_Controller_Action { public function indexAction() {}}');
        $module->cdNew('views/scripts')->createDir(strtolower($controller))->createFile('index.phtml')->put(ucfirst($moduleName) . ' Index');
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
        return APPLICATION_PATH . '..\\';
    }


    public function setPath($path = ''){
        if(!empty($path)){
            $this->_path = dirname($path);
        }
        return $this;
    }


    public function getPath(){
        return $this->_path;
    }




}
