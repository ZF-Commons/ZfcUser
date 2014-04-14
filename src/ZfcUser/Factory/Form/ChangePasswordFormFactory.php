<?php
namespace ZfcUser\Factory\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Form\ChangePasswordForm;

/**
 * Class ChangePasswordFormFactory
 * @package ZfcUser\Factory\Form
 */
class ChangePasswordFormFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ChangePasswordForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var $serviceLocator \Zend\Form\FormElementManager
         * @var $serviceManager \Zend\ServiceManager\ServiceManager
         */
        $serviceManager = $serviceLocator->getServiceLocator();

        $form = new ChangePasswordForm();
        $form->setInputFilter($serviceManager->get('InputFilterManager')->get('ZfcUser\InputFilter\ChangePasswordFilter'));

        return $form;
    }
}
