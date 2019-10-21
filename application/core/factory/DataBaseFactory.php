<?php

namespace application\core\factory;

use application\core\factory\FactoryInterface;
use application\lib\DbMysql;

/**
 * Class DataBaseFactory
 * Creates db driver instance
 *
 * @package application\core\factory
 */
class DataBaseFactory implements FactoryInterface
{
    const
        DRIVER_MYSQL = 'mysql',
        DRIVER_POSTGRE = 'postgre';

    /**
     * @param array $config
     * @return object|null
     */
    public function build($config = [])
    {
        $db = null;

        switch ($config['driver']) {
            case self::DRIVER_MYSQL:
                $db = new DbMysql($config);
                break;
            case self::DRIVER_POSTGRE:
                break;
            default:
                throw new \InvalidArgumentException('Unknown database driver: ' . $config['driver']);
        }

        return $db;
    }
}
