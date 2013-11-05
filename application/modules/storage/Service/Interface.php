<?php
interface Storage_Service_Interface
{
    public function store($pathFile, $extension);


    public function getIdentity();

    public function getType();

    public function map(Storage_Model_File $model);

    public function read($filePath);

    public function write($pathFile, $extension);

    public function remove(Storage_Model_File $model);

    public function move(Storage_Model_File $model);

    public function removeFile($path);

    public function filesize(Storage_Model_File $model);

    public function copy(Storage_Model_File $model);
}