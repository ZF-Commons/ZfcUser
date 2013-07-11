<?php

namespace ZfcUserTest\Form;

use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator\ClassMethods;
use ZfcUser\Form\RegisterFormFactory;
use ZfcUser\ModuleOptions;

class RegisterFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceManager
     */
    protected $sm;

    /**
     * @var RegisterFormFactory
     */
    protected $factory;


    public function setUp()
    {
        $this->sm      = new ServiceManager();
        $this->factory = new RegisterFormFactory();

        $this->sm->setService('ZfcUser\ModuleOptions', new ModuleOptions());
    }

    /**
     * @covers \ZfcUser\Form\RegisterFormFactory::createService
     */
    public function testStringHydrator()
    {
        $this->sm->get('ZfcUser\ModuleOptions')->setRegisterHydrator('Zend\Stdlib\Hydrator\ClassMethods');

        $form = $this->factory->createService($this->sm);
        $this->assertInstanceOf('ZfcUser\Form\RegisterForm', $form);
    }

    /**
     * @covers \ZfcUser\Form\RegisterFormFactory::createService
     */
    public function testSmHydrator()
    {
        $this->sm->setService('FooBar', new ClassMethods());
        $this->sm->get('ZfcUser\ModuleOptions')->setRegisterHydrator('FooBar');

        $form = $this->factory->createService($this->sm);
        $this->assertInstanceOf('ZfcUser\Form\RegisterForm', $form);
    }
}