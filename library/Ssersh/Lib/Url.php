<?php

class Ssersh_Lib_Url
{

    static function getBaseUrl($view, $configPath = true)
    {
        $baseUrl = null;

        if ($configPath) {
            $baseUrl = Zend_Registry::get('config')->baseUrl;
        }

        return empty($baseUrl) ? $view->serverUrl() : $baseUrl;
    }


    static function getUrl($path = '', $params = array(), $reset = true, $configPath = true){
        $path = array_reverse(explode('/', trim($path, '/')));

        $params['action']     = !empty($path[0]) ? $path[0] : null;
        $params['controller'] = !empty($path[1]) ? $path[1] : null;
        $params['module']     = !empty($path[2]) ? $path[2] : null;

        $view = new Zend_View();
        return static::getBaseUrl($view, $configPath) . $view->url($params, $route = 'default', $reset);
    }

    static function getPublicUrl($path = '')
    {
        $view = new Zend_View();
        return static::getBaseUrl($view) . rtrim(Zend_Controller_Front::getInstance()->getBaseUrl(), '/') . '/' . $path;
    }


    static function getRouteUrl($routName, $params = array())
    {
        $view = new Zend_View();
        return static::getBaseUrl($view) . $view->url($params, $routName, true);
    }



}
