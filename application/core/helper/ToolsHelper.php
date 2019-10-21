<?php

namespace application\core\helper;

use application\core\Application;

/**
 * Class ToolsHelper
 *
 * @package application\core\helper
 */
class ToolsHelper
{
    /**
     * @param $str
     * @return string
     */
    public static function getHashFromString($str): string
    {
        return hash(Application::$config['description_hash_algo'], $str);
    }
}
