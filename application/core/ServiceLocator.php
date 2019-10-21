<?php

namespace application\core;

use application\core\factory\FactoryInterface;

/**
 * Class ServiceLocator
 *
 * @package application\core
 */
class ServiceLocator
{
    /**
     * @var array
     */
    private $services = [];

    /**
     * @var array
     */
    private $instantiated = [];

    /**
     * @param string $class
     * @param $service
     */
    public function addInstance(string $class, $service)
    {
        $this->services[$class] = $service;
        $this->instantiated[$class] = $service;
    }

    /**
     * @param string $class
     * @param array $params
     */
    public function addClass(string $class, array $params = [])
    {
        $this->services[$class] = $params;
    }

    /**
     * @param string $interface
     * @return bool
     */
    public function has(string $class): bool
    {
        return isset($this->services[$class]) || isset($this->instantiated[$class]);
    }

    /**
     * Get instance by class name
     *
     * @param string $class
     * @return mixed
     */
    public function get(string $class)
    {
        if (isset($this->instantiated[$class])) {
            return $this->instantiated[$class];
        }

        if (!$this->has($class)) {
            throw new \InvalidArgumentException('Not registered class.');
        }

        // create object
        $object = new $class($this->services[$class]);
        // in case factory invoke build
        if (isset(class_implements($class)['application\core\factory\FactoryInterface']) || $object instanceof FactoryInterface) {
            $object = $object->build($this->services[$class]);
        }
        $this->instantiated[$class] = $object;

        return $object;
    }
}
