<?php

namespace ZfcUser\Factory\Form;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZfcUser\Form;

class Login implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceManager, $requestedName, array $options = null)
    {
        $options = $serviceManager->get('zfcuser_module_options');
        $form = new Form\Login(null, $options);

        $form->setInputFilter(new Form\LoginFilter($options));

        return $form;
    }
}
