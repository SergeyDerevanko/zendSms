<?php 
class User_Bootstrap extends Zend_Application_Module_Bootstrap
{
    protected function  _initAutoloadNamespaces()
    {
        $applicationAutoloader = $this->getResourceLoader();
        $applicationAutoloader->addResourceType('Hybrid', 'Hybrid', 'Hybrid');
    }
}