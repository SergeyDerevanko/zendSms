<?php
class Storage_AdminController extends Ikantam_Controller_Admin
{
    public function optionsAction(){
        $this->initOptionForForm('storage', 'service_id', 1);
        $this->initOptionForForm('storage', 'accessKey', '');
        $this->initOptionForForm('storage', 'secretKey', '');
        $this->initOptionForForm('storage', 'bucket', '');
        $this->initOptionForForm('storage', 'path', '');

        $services = new Storage_Model_Collections_Service();
        $services->getAll();
        $this->view->services = $services;

    }


    public function managerAction(){
        $files = new Storage_Model_Collections_File();
        $files->getAll();

        $this->view->files = $files;
    }


    public function deletefileAction(){
        $data = array('success' => true);
        $file = new Storage_Model_File($this->getParam('id', 0));

        if($file->getId()){
            $file->delete();
        }

        $this->_helper->json($data);
    }



}