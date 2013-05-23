<?php

namespace ZfcUserTest\Authentication\Adapter;

use ZfcUser\Authentication\Adapter\AdapterChainServiceFactory;

class AdapterChainServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The object to be tested.
     *
     * @var AdapterChainServiceFactory
     */
    protected $factory;

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var \ZfcUser\Options\ModuleOptions'
     */
    protected $options;

    /**
     * @var \Zend\EventManager\EventManagerInterface
     */
    protected $eventManager;

    /**
     * Prepare the object to be tested.
     */
    protected function setUp()
    {
        $this->serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');

        $this->options = $this->getMockBuilder('ZfcUser\Options\ModuleOptions')
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->serviceLocator->expects($this->any())
            ->method('get')
            ->with('zfcuser_module_options')
            ->will($this->returnValue($this->options));

        $this->eventManager = new \Zend\EventManager\EventManager;

        $this->factory = new AdapterChainServiceFactory();
    }

    /**
     * @covers \ZfcUser\Authentication\Adapter\AdapterChainServiceFactory::createService
     */
    public function testCreateService()
    {
        $this->options->expects($this->once())
            ->method('getAuthAdapters')
            ->will($this->returnValue(array()));

        $this->markTestIncomplete('Test needs to check inside attach adapter loop');

        $adapterChain = $this->factory->createService($this->serviceLocator);

        $this->assertInstanceOf('ZfcUser\Authentication\Adapter\AdapterChain', $adapterChain);
    }
}
