<?php
/**
 * Abstract model
 *
 * @method int getId()
 *
 */
abstract class Application_Model_Abstract extends Ikantam_Object
{
    protected $_classBackend = null;

    public function __construct($id = null){

        $className = explode('_', get_class($this));
        $this->_classBackend = 'Application_Model_Backend_' . end($className);

        /*if (is_numeric($id)) {
            $this->getById($id);
        }*/

    }


    public function getById($id){
        $this->_getBackend()->getById($this, $id);
        return $this;
    }


    protected function _getBackend(){
        return new $this->_classBackend();
    }











}
