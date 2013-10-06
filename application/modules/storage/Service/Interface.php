<?php
interface Storage_Service_Interface
{
    public function getIdentity();

    public function getType();

    public function map(Storage_Model_File $model);

    public function store(Storage_Model_File $model, $file);

    public function read(Storage_Model_File $model);

    public function write($data);

    public function remove(Storage_Model_File $model);

    public function removeFile($path);

}