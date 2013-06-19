<?php

namespace ZfcUserTest\Service;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Options\ModuleOptions;
use ZfcUser\Service\LoginServiceFactory;
use ZfcUserTest\Asset\ChainAdapter;
use ZfcUserTest\Asset\LoginListener;

class LoginServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LoginServiceFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new LoginServiceFactory();
    }

    /**
     * @covers \ZfcUser\Service\LoginServiceFactory::createService
     */
    public function testInstanceReturned()
    {
        $options = new ModuleOptions();
        $options->setLoginPlugins(array(new LoginListener()));
        $options->setLoginAdapters(array(new ChainAdapter()));

        $sm = new ServiceManager();
        $sm->setService('ZfcUser\Options\ModuleOptions', $options);

        $instance = $this->factory->createService($sm);

        $this->assertInstanceOf('ZfcUser\Service\LoginService', $instance);
    }
}
