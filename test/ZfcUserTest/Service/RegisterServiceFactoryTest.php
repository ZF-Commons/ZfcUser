<?php

namespace ZfcUserTest\Service;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Options\ModuleOptions;
use ZfcUser\Service\RegisterServiceFactory;
use ZfcUserTest\Asset\RegisterListener;

class RegisterServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RegisterServiceFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new RegisterServiceFactory();
    }

    /**
     * @covers \ZfcUser\Service\RegisterServiceFactory::createService
     */
    public function testInstanceReturned()
    {
        $options = new ModuleOptions();
        $options->setRegisterPlugins(array(new RegisterListener()));

        $sm = new ServiceManager();
        $sm->setService('ZfcUser\Options\ModuleOptions', $options);

        $instance = $this->factory->createService($sm);

        $this->assertInstanceOf('ZfcUser\Service\RegisterService', $instance);
    }
}
