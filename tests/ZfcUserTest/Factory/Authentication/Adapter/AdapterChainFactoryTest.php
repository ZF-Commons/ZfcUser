<?php

namespace ZfcUserTest\Factory\Authentication\Adapter;

use ZfcUser\Factory\Authentication\Adapter\AdapterChainFactory;

class AdapterChainFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The object to be tested.
     *
     * @var AdapterChainFactory
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

    public function testCreateService()
    {
        $this->serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        
        $this->options = $this->getMockBuilder('ZfcUser\Options\ModuleOptions')
             ->disableOriginalConstructor()
             ->getMock();

        $adapters = array(
            9999 => 'adapter1',
            20   => 'adapter2',
        );
        $adapter1 = $this->getMock('Zend\Authentication\Adapter\AbstractAdapter');
        $adapter2 = $this->getMock('Zend\Authentication\Adapter\AbstractAdapter');
        
        $this->options->expects($this->atLeastOnce())
             ->method('getAuthAdapters')
             ->will($this->returnValue($adapters));

        $locatorMap = array(
            array('zfcuser_module_options', $this->options),
            array('adapter1', $adapter1),
            array('adapter2', $adapter2),
        );
        
        $this->serviceLocator->expects($this->atLeastOnce())
            ->method('get')
            ->will($this->returnValueMap($locatorMap));

        $this->factory = new AdapterChainFactory();
        
        $adapterChain = $this->factory->createService($this->serviceLocator);
        $this->assertInstanceOf('ZfcUser\Authentication\Adapter\AdapterChain', $adapterChain);
    }
}
