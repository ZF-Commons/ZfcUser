<?php
namespace ZfcUser\Factory\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Form\Registration;

/**
 * Class RegistrationFactory
 * @package ZfcUser\Factory\Form
 */
class RegistrationFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Registration
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var $options        \ZfcUser\Options\RegistrationOptionsInterface
         * @var $serviceLocator \Zend\Form\FormElementManager
         * @var $serviceManager \Zend\ServiceManager\ServiceManager
         */
        $serviceManager = $serviceLocator->getServiceLocator();
        $options = $serviceManager->get('zfcuser_module_options');
        $class = $options->getUserEntityClass();

        $form =  new Registration(null, $options);
        $form->setHydrator($serviceManager->get('HydratorManager')->get('ZfcUser\Hydrator\Registration'));
        $form->setInputFilter($serviceManager->get('InputFilterManager')->get('ZfcUser\InputFilter\Registration'));
        $form->setObject(new $class);

        return $form;
    }
}
