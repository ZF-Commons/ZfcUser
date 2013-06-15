<?php

namespace ZfcUser\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param $input
     * @return array|object
     */
    protected function get(ServiceLocatorInterface $serviceLocator, $input)
    {
        if (is_string($input)) {
            if ($serviceLocator->has($input)) {
                $input = $serviceLocator->get($input);
            } else {
                $input = new $input();
            }
        }
        return $input;
    }
}