<?php

namespace ZfcUser\Service\Factory;

use ZfcUser\Service\RegisterService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegisterServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @throws \InvalidArgumentException
     * @return RegisterService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if (!$serviceLocator->has('ZfcUser\Mapper\RegisterMapper')) {
            // todo: throw exception
            echo 'no register mapper has been set, have you installed a data provider?';
            exit;
        }

        /** @var \ZfcUser\Options\ModuleOptions $options */
        $options = $serviceLocator->get('ZfcUser\Options\ModuleOptions');

        /** @var \ZfcUser\Mapper\RegisterMapperInterface $mapper */
        $mapper = $serviceLocator->get('ZfcUser\Mapper\RegisterMapper');

        /** @var \ZfcUser\Form\RegisterForm $form */
        $form = $serviceLocator->get('ZfcUser\Form\RegisterForm');

        return new RegisterService($form, $mapper, $options);
    }
}