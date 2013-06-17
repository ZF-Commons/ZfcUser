<?php

namespace ZfcUserTest\Authentication;

use ZfcUser\Authentication\AdapterChain;
use ZfcUserTest\Asset\ChainAdapter;

class AdapterChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AdapterChain
     */
    protected $chain;

    public function setUp()
    {
        $this->chain = new AdapterChain();
    }

    /**
     * @covers \ZfcUser\Authentication\AdapterChain::getEventManager
     */
    public function testGetEventManagerIsLazyLoaded()
    {
        $this->assertInstanceOf('Zend\EventManager\EventManager', $this->chain->getEventManager());
    }

    /**
     * @covers \ZfcUser\Authentication\AdapterChain::getEvent
     */
    public function testGetEventIsLazyLoaded()
    {
        $this->assertInstanceOf('ZfcUser\Authentication\ChainEvent', $this->chain->getEvent());
    }

    /**
     * @covers \ZfcUser\Authentication\AdapterChain::setAdapters
     */
    public function testSetAdapters()
    {
        $adapters = array(
            -1       => new ChainAdapter(),
            10       => new ChainAdapter(),
            'nonint' => new ChainAdapter()
        );

        $this->chain->setAdapters($adapters);
        $this->assertCount(3, $this->chain->getAdapters());

        $em        = $this->chain->getEventManager();
        $listeners = $em->getListeners('test')->toArray();

        $this->assertCount(3, $listeners);

        foreach (array(-1, 10, 100) as $key => $priority) {
            $metadata = $listeners[$key]->getMetadata();
            $this->assertEquals($priority, $metadata['priority']);
        }
    }

    /**
     * @covers \ZfcUser\Authentication\AdapterChain::authenticate
     */
    public function testAuthenticate()
    {
        $this->chain->setEventParams(array(
            'foo'    => 'bar',
            'result' => true,
        ));

        $event = $this->chain->getEvent();

        $this->chain->addAdapter(new ChainAdapter());
        $result = $this->chain->authenticate();

        $this->assertTrue($event->getParam('result'));
        $this->assertTrue($result->isValid());
        $this->assertEquals('foo', $result->getIdentity());

        $this->chain->setEventParams(array(
            'result' => false
        ));

        $result = $this->chain->authenticate();
        $this->assertFalse($event->getParam('result'));
        $this->assertFalse($result->isValid());
        $this->assertNull($result->getIdentity());
        $this->assertEquals(array('failure'), $result->getMessages());
    }
}