<?php

namespace application\core;

use application\core\View;
use application\core\Application;
use application\core\Request;

/**
 * Class Router
 *
 * @package application\core
 */
class Router
{
    protected $activeRoutes = [];
    protected $requestParams = [];
    private $request;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->setActiveRoutes();
        $this->request = Application::$serviceLocator->get(Request::class);
        $this->requestParams = $this->request->getRequestParams();
    }

    /**
     * Set available routes @property this->activeRoutes
     */
    protected function setActiveRoutes()
    {
        $routes = Application::$config['routes'];
        foreach ($routes as $url => $val) {
            $url = '#^' . $url . '$#';
            $this->activeRoutes[$url] = $val;
        }
    }

    /**
     * Match requested route from available
     * Return true if route matched and set @property $this->currentRouteParts
     *
     * @return bool
     */
    public function match(): bool
    {
        $currentRoute = $this->request->getCurrentRoute();

        foreach ($this->activeRoutes as $route => $parts) {
            if (preg_match($route, $currentRoute, $matches)) {
                $this->currentRouteParts = $parts;
                return true;
            }
        }
        return false;
    }

    /**
     * Execute Controller action
     */
    public function run()
    {
        if ($this->match()) {
            $class = 'application\controllers\\' . ucfirst($this->currentRouteParts['controller']) . 'Controller';
            if (class_exists($class)) {
                $action = $this->currentRouteParts['action'] . 'Action';
                if (method_exists($class, $action)) {
                    $controller = new $class($this->currentRouteParts, $this->requestParams);
                    $controller->$action();
                } else {
                    View::errorCode(404);
                }
            } else {
                View::errorCode(404);
            }
        } else {
            View::errorCode(404);
        }
    }

    /**
     * Redirects the browser to the specified URL.
     *
     * @param string $route - 'controller/action'
     * @param bool $permanent
     */
    public function redirect(string $route, bool $permanent = true)
    {
        $url = $this->request->getBaseUrl() . '/' . ltrim($route, '/');
        header('Location: ' . $url, true, $permanent ? 301 : 302);

        exit();
    }
}
