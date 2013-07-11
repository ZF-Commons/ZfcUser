<?php

namespace ZfcUserTest\Form;

use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator\ClassMethods;
use ZfcUser\Form\PasswordStrategy;
use ZfcUser\Form\RegisterHydratorFactory;
use ZfcUser\ModuleOptions;

class RegisterHydratorFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceManager
     */
    protected $sm;

    /**
     * @var RegisterHydratorFactory
     */
    protected $factory;


    public function setUp()
    {
        $this->sm      = new ServiceManager();
        $this->factory = new RegisterHydratorFactory();
        $options       = new ModuleOptions();

        $this->sm->setService('ZfcUser\ModuleOptions', $options);
        $this->sm->setService('ZfcUser\Form\PasswordStrategy', new PasswordStrategy($options));
    }

    /**
     * @covers \ZfcUser\Form\RegisterHydratorFactory::createService
     */
    public function testSmHydrator()
    {
        $this->sm->setService('FooBar', new ClassMethods());
        $this->sm->get('ZfcUser\ModuleOptions')->setRegisterHydrator('FooBar');

        $form = $this->factory->createService($this->sm);
        $this->assertInstanceOf('ZfcUser\Form\RegisterHydrator', $form);
    }
}