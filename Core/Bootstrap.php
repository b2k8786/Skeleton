<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Core;

/**
 * Description of Bootstrap
 *
 * @author anil
 */
class Bootstrap
{

    private static $instance;
    protected static $requestURI;
    protected $controller;
    protected $action;
    protected $slugs;

    function __construct()
    {
        self::$instance   = &$this;
        self::$requestURI = str_replace(APP_ROOT, '', str_replace('//', '/', $_SERVER['REQUEST_URI']));
    }

    /**
     * Singleton method
     * @return object
     */
    static function getInstance()
    {
        return empty(self::$instance) ? (new Bootstrap()) : self::$instance;
    }

    /**
     * Start session if needed
     */
    function session()
    {
        if (!API_MODE) {
            session_name('SKELETON');
            session_start();
        } else {
            ini_set('session.use_cookies', '0');
        }
    }
    /**
     * Error reporting as per config
     */
    function errorReporting()
    {
        if (ERROR_REPOTING) {
            ini_set('display_errors', '1');
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', '1');
        }
    }

    /**
     * 
     * @return string if path available
     * @return boolean if path is empty 
     */
    function path()
    {
        if (!empty(self::$requestURI))
            return parse_url(self::$requestURI, PHP_URL_PATH);
    }

    /**
     * 
     * @return string if path available
     * @return string fragment #value
     */
    function fragment()
    {
        if (!empty(self::$requestURI))
            return parse_url(self::$requestURI, PHP_URL_FRAGMENT);
    }

    /**
     * Find in Routes
     */
    function populateRoute()
    {
        global $routes;
        $URI = explode('/', $this->path());
        if (empty($URI[0]))
            array_shift($URI);
        if (empty($URI[0]) && !empty($route['/'])) {
            $targetRoute = explode('/', $route['/']);

            $this->controller = empty($targetRoute[0]) ? null : $targetRoute[0];
            $this->action     = empty($targetRoute[1]) ? null : $targetRoute[1];
        } else if (!empty($URI) && !empty($routes)) {
            foreach ($routes as $request => $route) {
                $requestedRoute = explode('/', $request);
                if (ucfirst($URI[0]) == $requestedRoute[0] && (empty($URI[1]) || ($URI[1] == $requestedRoute[1] || $requestedRoute[1] == "*"))) {
                    $targetRoute      = explode('/', $route);
                    $this->controller = empty($targetRoute[0]) ? null : $targetRoute[0];
                    array_shift($URI);
                    $this->action     = empty($targetRoute[1]) ? null : $targetRoute[1];
                    if (!empty($URI) && $URI[0] == $requestedRoute[0])
                        array_shift($URI);
                }
            }
        }
        if (!empty($this->controller) && !empty($this->action))
            $this->slugs = empty($URI) ? [] : $URI;
    }

    function populateURI()
    {
        $URI = explode('/', $this->path());
        if (empty($URI[0]))
            array_shift($URI);

        $this->controller = empty($URI) ? null : ucfirst(array_shift($URI));
        $this->action     = empty($URI) ? null : array_shift($URI);
        $this->slugs      = empty($URI) ? [] : $URI;
    }

    /**
     * 
     * @global array() $route  from ./config.php
     * @global array() $method from ./config.php
     * @return array() parameters to action 
     */
    function parseUrlPath()
    {
        $this->populateRoute();
        if (empty($this->controller))
            $this->populateURI();

        // Try to load defaults
        if (empty($this->controller))
            $this->controller = DEFAULT_CONTROLLER;
        if (empty($this->action)) {
            global $method;
            $this->action = $method[$_SERVER['REQUEST_METHOD']];
        }

        return empty($this->slugs) ? [] : $this->slugs;
    }

    /**
     * 
     * @param array() $slugs
     * @return null
     */
    function app()
    {
        try {
            $controllerName = CONTROLLER_NS . $this->controller;
            call_user_func_array(array((new $controllerName()), $this->action), $this->slugs);
        } catch (\Exception $e) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
            include_once '_pages/404.php';
            print_r($e);
            return;
        }
    }
}
