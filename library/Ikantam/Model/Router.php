<?php

class Ikantam_Model_Router extends Ikantam_Model_Abstract{


    /* SET PUBLIC FUNCTION */
    public function create($data){
        $this->setName($data['name'])
            ->setUrl($data['url'])
            ->setModule($data['module'])
            ->setController($data['controller'])
            ->setAction($data['action'])
            ->save();
        return $this;
    }


    public static function add($name, $url, $module, $controller, $action){
        $route = new Ikantam_Model_Router();
        $data = array(
            'name' => $name,
            'url' => $url,
            'module' => $module,
            'controller' => $controller,
            'action' => $action
        );
        $route->create($data);
        return $route;
    }
}
