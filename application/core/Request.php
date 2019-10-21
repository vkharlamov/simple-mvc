<?php

namespace application\core;

/**
 * Class Request
 *
 * @package application\core
 */
class Request
{
    /** @var string route 'controller/action' */
    public $currentRoute;

    /** @var array ['post' => array, 'get' => array] */
    protected $requestParams = [];

    /**
     * Set @var $this ->requestParams
     *
     * @return array with keys ['post' => array, 'get' => array]
     */
    public function getRequestParams(): array
    {
        if (!empty($this->requestParams)) {
            return $this->requestParams;
        }
        // $_GET
        $queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        mb_parse_str($queryString, $paramsGet);
        $this->requestParams['get'] = $paramsGet;

        // $_POST
        $this->requestParams['post'] = $_POST;

        return $this->requestParams;
    }

    /**
     * Get current request route.
     * Set @var $this ->currentRoute
     *
     * @return string
     */
    public function getCurrentRoute(): string
    {
        if (empty($this->currentRoute)) {
            $currentRoute = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $this->currentRoute = ltrim($currentRoute, '/');
        }
        return $this->currentRoute;
    }

    /**
     * Get base url with protocol
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'
                ? "https"
                : "http") . "://" . $_SERVER['HTTP_HOST'];
    }

    /**
     * @return string Request method name in upper case
     */
    public function getMethod(): string
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }

        return 'GET';
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->getMethod() === 'POST';
    }

    /**
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->getMethod() === 'GET';
    }
}
