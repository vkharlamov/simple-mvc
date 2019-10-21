<?php

namespace application\core\factory;

/**
 * Interface FactoryInterface
 *
 * @package application\core\factory
 */
interface FactoryInterface
{
    /**
     * @param array $params
     * @return object Instance of connected data provider
     */
    public function build($params = []);
}
