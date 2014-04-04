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
     * @var \ZfcUser\Options\ModuleOptions
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

        $this->serviceLocator->expects($this->at(0))
            ->method('get')
            ->with('zfcuser_module_options')
            ->will($this->returnValue($this->options));

        $this->eventManager = $this->getMock('Zend\EventManager\EventManager');

        $this->factory = new AdapterChainServiceFactory();
    }

    /**
     * @covers \ZfcUser\Authentication\Adapter\AdapterChainServiceFactory::createService
     */
    public function testCreateService()
    {
        $adapterNames = array(100 => 'adapter1', 200 => 'adapter2');
        $this->options->expects($this->once())
                      ->method('getAuthAdapters')
                      ->will($this->returnValue($adapterNames));

        $i = 1;
        foreach ($adapterNames as $priority => $name) {
            $adapter = $this->getMock('ZfcUser\Authentication\Adapter\AbstractAdapter', array('authenticate', 'logout'));

            $this->serviceLocator->expects($this->at($i))
                 ->method('get')
                 ->with($name)
                 ->will($this->returnValue($adapter));

            $i++;
        }

        $adapterChain = $this->factory->createService($this->serviceLocator);

        $this->assertInstanceOf('ZfcUser\Authentication\Adapter\AdapterChain', $adapterChain);
        $this->assertEquals(array('authenticate', 'logout'), $adapterChain->getEventManager()->getEvents());
    }
}
