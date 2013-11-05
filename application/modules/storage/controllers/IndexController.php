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

            $_file = new Storage_Model_Image();
            $_file->create($data);

            $fdf = clone $_file;
            $fdf->crop(0, 0, 100, 100);


            /*$image = Ikantam_Image::factory();
            $image->open($_file->getMap())
                ->resize(100, 100)
                ->rotate(40)
                ->write($_file->getMap())
                ->destroy();*/
            echo "<a href='" . $_file->getHref() . "'>" . $_file->getHref() . "</a>";
        }
    }


    public function cropAction(){

    }


    public function croploadAction(){
        $data = array('success' => false);

        $upload = new Zend_File_Transfer();
        $file =  $upload->getFileInfo();

        if($file){
            $file = $file['file'];

            $data = array(
                'name' => $file['name'],
                'path' => $file['tmp_name']
            );

            $_file = new Storage_Model_Image();
            $_file->create($data);

            $avatar = new User_Model_Avatar();
            $avatar->create(array('file_id' => $_file->getId()));

            $data['object_id'] = $avatar->getId();
            $data['img_url'] = $_file->getHref();
            $data['success'] = true;
        }

        $this->_helper->json($data);
    }


    public function cropcropAction(){
        $avatar = new User_Model_Avatar($this->getParam('object_id'));
        $avatar->icrop($this->getAllParams());
        print_r($this->getAllParams());
        exit;
    }
}