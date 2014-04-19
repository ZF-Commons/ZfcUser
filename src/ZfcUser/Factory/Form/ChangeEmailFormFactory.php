<?php
namespace ZfcUser\Factory\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Form\ChangeEmailForm;

/**
 * Class ChangeEmailFormFactory
 * @package ZfcUser\Factory\Form
 */
class ChangeEmailFormFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ChangeEmailForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var \Zend\Form\FormElementManager       $serviceLocator
         * @var \Zend\ServiceManager\ServiceManager $serviceManager
         */
        $serviceManager = $serviceLocator->getServiceLocator();

        $form = new ChangeEmailForm();
        $form->setInputFilter($serviceManager->get('InputFilterManager')->get('ZfcUser\InputFilter\ChangeEmailFilter'));

        return $form;
    }
}
