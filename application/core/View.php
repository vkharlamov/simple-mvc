<?php

namespace application\core;

/**
 * Class View
 *
 * @package application\core
 */
class View
{
    public $path;
    public $route;
    public $layout = 'default';

    /**
     * View constructor.
     *
     * @param $route
     */
    public function __construct($route)
    {
        $this->route = $route;
        $this->path = $route['controller'] . '/' . $route['action'];
    }

    /**
     * Render template file
     *
     * @param $title
     * @param array $vars
     */
    public function render($title, $vars = [])
    {
        extract($vars);
        $renderFile = static::resolveViewPath($this->path . '.php');
        if (file_exists($renderFile)) {
            ob_start();
            require $renderFile;
            $content = ob_get_clean();
            $layout = static::resolveViewPath('layouts/' . $this->layout . '.php');
            require $layout;
        }
    }

    /**
     * @param $code
     */
    public static function errorCode($code)
    {
        http_response_code($code);
        $path = static::resolveViewPath('errors/' . $code . '.php');
        if (file_exists($path)) {
            require_once $path;
        }
        exit;
    }

    /**
     * Terminate application with message
     *
     * @param $status
     * @param $message
     */
    public function message($status, $message)
    {
        exit(json_encode(['status' => $status, 'message' => $message]));
    }

    /**
     * Resolve view path
     *
     * @param $filePath
     * @return string
     */
    public static function resolveViewPath($filePath)
    {
        return ABSOLUTE_ROOT_PATH . 'application/views/' . ltrim($filePath, '/');
    }
}
