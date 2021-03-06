<?php

abstract class Storage_Service_Abstract implements Storage_Service_Interface
{
    public function __construct(array $config = array()){
        if( !empty($config['service_id']) ) {
            $this->_config = $config;
        }
    }


    public function generate($extension){
        $path = '';
        $base  = 255;
        $tmp = str_replace('.', '', microtime());
        do {
            $mod = ( $tmp % $base );
            $tmp -= $mod;
            $tmp /= $base;
            $path .= sprintf("%02x", $mod) . '/';
        } while( $tmp > 0 );

        $path .= time();
        return $path . '.' . $extension;
    }



    protected function _write($file, $data){
        if( function_exists('umask') ) {
            $oldUmask = umask();
            umask(0);
        }

        $code = 0;
        if( !@file_put_contents($file, $data) ) {
            if( is_file($file) ) {
                @chmod($file, 0666);
            } else if( is_dir(dirname($file)) ) {
                @chmod(dirname($file), 0777);
            } else {
                @mkdir(dirname($file), 0777, true);
            }

            if( !@file_put_contents($file, $data) ) {
                $code = 1;
            }
        }

        if( function_exists('umask') ) {
            umask($oldUmask);
        }

        if( 1 == $code ) {
            throw new Zend_Exception(sprintf('Unable to write to file: $s', $file));
        }
    }


    public function filesize(Storage_Model_File $model){
        if($size = filesize($this->map($model))){
            return $size;
        }
        return 0;
    }


    public function remove(Storage_Model_File $model){
        $storagePath = $model->getStoragePath();
        if( !empty($storagePath)){
            $this->_delete($this->map($model));
        }
    }


    public function move(Storage_Model_File $model){
        $storagePath = $model->getStoragePath();
        if( !empty($storagePath)){
            $newPath = $this->copy($model);
            $this->_delete($this->map($model));
            $model->setStoragePath($newPath)->save();
        }
    }


    protected function _delete($file){
        $code = 0;
        if( is_file($file) ) {
            if( !@unlink($file) ) {
                @chmod($file, 0777);
                if( !@unlink($file) ) {
                    $code = 1;
                }
            }
        }

        if( 1 == $code ) {
            throw new Zend_Exception('Unable to delete file: '.$file);
        }
    }


    protected function _copy($from, $to){
        if( function_exists('umask') ) {
            $oldUmask = umask();
            umask(0);
        }

        $code = 0;
        if( !is_file($from) ) {
            $code = 1;
        } else if( !@copy($from, $to) ) {
            @mkdir(dirname($to), 0777, true);
            @chmod(dirname($to), 0777);
            if( !@copy($from, $to) ) {
                $code = 1;
            }
        }

        if( function_exists('umask') ) {
            umask($oldUmask);
        }

        if( 1 == $code ) {
            throw new Zend_Exception('Unable to copy file ('.$from.') -> ('.$to.')');
        }
    }










    protected $_identity;

    // General




    public function getIdentity()
    {
        return $this->_identity;
    }


    protected function _checkFile($file, $mode = 06)
    {
        // @todo This is fubared, fix up later
        //if( preg_match('/[^a-z0-9\\/\\\\_.:-]/i', $file) )
        //if( preg_match('/[^a-z0-9 \\/\\\\_.:-]/i', $file) )
        //{
        //throw new Storage_Service_Exception(sprintf('Security check: Illegal character in filename: %s', $file));
        //}

        if( $mode && !file_exists($file) )
        {
            throw new Storage_Service_Exception('File does not exist: '.$file);
        }

        if( ($mode & 04) && (!is_readable($file)) )
        {
            throw new Storage_Service_Exception('File not readable: '.$file);
        }

        if( ($mode & 02) && (!is_writable($file)) )
        {
            throw new Storage_Service_Exception('File not writeable: '.$file);
        }

        if( ($mode & 01) && (!is_executable($file)) )
        {
            throw new Storage_Service_Exception('File not executable: '.$file);
        }
    }

    protected function _mkdir($path, $mode = 0777)
    {
        // Change umask
        if( function_exists('umask') ) {
            $oldUmask = umask();
            umask(0);
        }

        // Change perms
        $code = 0;
        if( is_dir($path) ) {
            @chmod($path, $mode);
        } else if( !@mkdir($path, $mode, true) ) {
            $code = 1;
        }

        // Revert umask
        if( function_exists('umask') ) {
            umask($oldUmask);
        }

        // Respond
        if( 1 == $code ) {
            throw new Zend_Exception(sprintf('Could not create folder: %s', $path));
        }
    }

    protected function _move($from, $to)
    {
        // Change umask
        if( function_exists('umask') ) {
            $oldUmask = umask();
            umask(0);
        }

        // Move
        $code = 0;
        if( !is_file($from) ) {
            $code = 1;
        } else if( !@rename($from, $to) ) {
            @mkdir(dirname($to), 0777, true);
            if( !@rename($from, $to) ) {
                $code = 1;
            }
        }

        // Revert umask
        if( function_exists('umask') ) {
            umask($oldUmask);
        }

        if( 1 == $code ) {
            throw new Zend_Exception('Unable to move file ('.$from.') -> ('.$to.')');
        }
    }







    protected function _read($file)
    {
        if( !@file_get_contents($file) ) {
            throw new Zend_Exception('Unable to read file: '.$file);
        }
    }



}
