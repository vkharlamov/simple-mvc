<?php

namespace application\core\helper;

use application\core\Application;

/**
 * Class GridHelper
 *
 * @package application\core\helper
 */
class GridHelper
{
    /**
     * @param array $params
     * @param string $route
     * @return string
     */
    public static function renderUrl(array $params = [], string $route = ''): string
    {
        $baseUrl = Application::getRequest()->getBaseUrl();

        if (!empty($route)) {
            $url = $baseUrl . '/' . ltrim($route) . '?' . http_build_query($params);
        } else {
            $route = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
            $route = ltrim($route, '/');
            $url = "${baseUrl}/{$route}?" . http_build_query($params);
        }

        return $url;
    }
}
