<?php
namespace ZfcUser\Factory\Authentication\CredentialProcessor;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Crypt\Password\Bcrypt;

class BcryptFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');

        return new Bcrypt(array(
            'cost' => $options->getPasswordCost()
        ));
    }
}
