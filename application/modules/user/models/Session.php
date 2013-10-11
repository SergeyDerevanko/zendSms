<?php
class User_Model_Session  extends Ikantam_Model_Abstract{

    public $_data = null;
    private static $_instance_ = null;


    public function __construct($id = null){
        $this->_data = new Zend_Session_Namespace();
    }


    public static function instance (){
        if(!self::$_instance_ instanceof self) {
            self::$_instance_ = new self();
        }
        return self::$_instance_;
    }


    public static function loginUser(){
        return new User_Model_User(self::instance()->getUserId());
    }


    public static function loginUserId(){
        $userId = self::loginUser()->getId();
        return $userId ? $userId : 0;
    }


    public static function isLogin(){
        return self::loginUserId() ? true : false;
    }


    public static function logout(){
        return self::instance()->setUserId(0);
    }


    public function __call($method, $args){
        $key = substr($method, 3);

        switch (substr($method, 0, 3)) {
            case 'get' :
                $data = $this->_data->$key;
                return $data;
                break;
            case 'set' :
                $this->_data->$key = isset($args[0]) ? $args[0] : null;
                return $this;
                break;
            case 'uns' :
                if(isset($this->_data->$key)) unset($this->_data->$key);
                return $this;
                break;
            case 'has' :
                return isset($this->_data->{$key});
                break;
        }
        throw new Exception("Invalid method " . get_class($this) . "::" . $method . "(" . print_r($args, 1) . ")");
    }


    public function setFlashData($key, $value, $expirationHops = 1){
        $this->_data->$key = $value;
        $this->_data->setExpirationHops($expirationHops, $key);

        return $this;
    }


    public function getFlashData($key){
        return $this->_data->$key;
    }

}