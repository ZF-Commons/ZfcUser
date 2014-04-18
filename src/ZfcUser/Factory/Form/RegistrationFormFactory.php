<?php
namespace ZfcUser\Factory\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Form\RegistrationForm;

/**
 * Class RegistrationFormFactory
 * @package ZfcUser\Factory\Form
 */
class RegistrationFormFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return RegistrationForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var \ZfcUser\Options\RegistrationOptionsInterface   $registrationOptions
         * @var \Zend\Form\FormElementManager                   $serviceLocator
         * @var \Zend\ServiceManager\ServiceManager             $serviceManager
         */
        $serviceManager         = $serviceLocator->getServiceLocator();
        $registrationOptions    = $serviceManager->get('zfcuser_module_options');
        $className              = $registrationOptions->getUserEntityClass();

        $form = new RegistrationForm(null, $registrationOptions);
        $form->setHydrator($serviceManager->get('HydratorManager')->get('ZfcUser\Hydrator\RegistrationHydrator'));
        $form->setInputFilter($serviceManager->get('InputFilterManager')->get('ZfcUser\InputFilter\RegistrationFilter'));
        $form->setObject(new $className);

        return $form;
    }
}
