<?php

namespace application\core;

use application\core\Application;
use application\core\View;

/**
 * Class Controller
 * Parent class of controllers
 *
 * @package application\core
 */
abstract class Controller
{
    /** @var array [controller, action] */
    public $route;

    /** @var array [get => array, post => array] */
    public $requestParams;

    /** @var \application\core\View */
    public $view;

    /** @var object \application\core\Request */
    public $request;

    /**
     * Controller constructor.
     *
     * @param array $route
     * @param array $requestParams
     */
    public function __construct(array $route, array $requestParams)
    {
        $this->route = $route;
        $this->requestParams = $requestParams;
        $this->view = new View($route);
        $this->request = Application::$serviceLocator->get(Request::class);
    }
}
