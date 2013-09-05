<?php

class DbController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $test = new Application_Model_Collections_Test();

        print_r($test->getAll());
        exit;
    }
}





