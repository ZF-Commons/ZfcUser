<?php

namespace ZfcUser\Extension;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ManagerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @throws Exception\MissingTypeException
     * @throws Exception\InvalidExtensionException
     * @return Manager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \ZfcUser\ModuleOptions $options */
        $options = $serviceLocator->get('ZfcUser\ModuleOptions');
        $manager = new Manager(new Config($options->getExtensions()));

        $extensions = $options->getExtensions();

        foreach ($extensions as $spec) {
            if (is_string($spec)) {
                $spec = array('type' => $spec);
            }

            $type = isset($spec['type']) ? $spec['type'] : null;
            if (!$type) {
                throw new Exception\MissingTypeException();
            }

            if (is_string($type)) {
                if ($serviceLocator->has($type)) {
                    $type = $serviceLocator->get($type);
                } else if (class_exists($type)) {
                    $type = new $type();
                }
            }

            if (!$type instanceof ExtensionInterface) {
                throw new Exception\InvalidExtensionException(sprintf(
                    'Extension of type %s is invalid; must implement %s\ExtensionInterface',
                    (is_object($type) ? get_class($type) : gettype($type)),
                    __NAMESPACE__
                ));
            }

            if (isset($spec['options'])) {
                $type->setOptions($spec['options']);
            }
            $manager->add($type);
        }

        return $manager;
    }
}