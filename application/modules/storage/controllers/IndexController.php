<?php
class Storage_IndexController extends Ikantam_Controller_Front
{
    public function indexAction()
    {

        $upload = new Zend_File_Transfer();
        $file =  $upload->getFileInfo();

        if($file){
            $file = $file['file'];

            $data = array(
                'name' => $file['name'],
                'path' => $file['tmp_name']
            );

            $_file = new Storage_Model_File();
            $_file->create($data);
            $image = Ikantam_Image::factory();
            $image->open($_file->getMap())
                ->resize(100, 100)
                ->rotate(40)
                ->write($_file->getMap())
                ->destroy();
            echo $_file->getHref();
        }
    }
}