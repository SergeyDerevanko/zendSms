<?php

class Ikantam_Lib_System_Reaction
{
    static $_reactions = null;


    /* functional reaction to call static functions */
    public static function call($methodName, $args = null){
        if(!self::$_reactions){
            self::$_reactions = new Zend_Config_Xml( APPLICATION_PATH.'/configs/react.xml');
        }
        switch (substr($methodName, 0, 5)) {
            case 'react' :

                /* processing of the query, and determining the induction method */
                $key = substr($methodName, 5);
                $result = explode('_', strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $key)));
                $reactName = substr($key, 0, strlen($result[0]));
                if(!empty(self::$_reactions->{$reactName})){
                    $class = self::$_reactions->{$reactName}->class;
                    $reactMetod = lcfirst(substr($key, strlen($reactName)));

                    /* of the reaction */
                    if(method_exists($class, $reactMetod)) {
                        print $class."::".$reactMetod.'()';
                        exit;
                        return $class::$reactMetod();
                    }

                }
                break;
            default:
                return null;
                break;
        }
        return null;
    }

}
