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
     * @covers \ZfcUser\Authentication\AdapterChain::setEventManager
     */
    public function testGetEventManagerIsLazyLoaded()
    {
        $this->assertInstanceOf('Zend\EventManager\EventManager', $this->chain->getEventManager());
    }

    /**
     * @covers \ZfcUser\Authentication\AdapterChain::getEvent
     * @covers \ZfcUser\Authentication\AdapterChain::setEvent
     */
    public function testGetEventIsLazyLoaded()
    {
        $this->assertInstanceOf('ZfcUser\Authentication\ChainEvent', $this->chain->getEvent());
    }

    /**
     * @covers \ZfcUser\Authentication\AdapterChain::addAdapter
     * @covers \ZfcUser\Authentication\AdapterChain::setAdapters
     * @covers \ZfcUser\Authentication\AdapterChain::getAdapters
     */
    public function testAdapters()
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

        $this->assertEquals(array_values($adapters), $this->chain->getAdapters());
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

    /**
     * @covers \ZfcUser\Authentication\AdapterChain::setEventParams
     * @covers \ZfcUser\Authentication\AdapterChain::getEventParams
     */
    public function testParams()
    {
        $expected = array('foo', 'bar');
        $this->chain->setEventParams($expected);
        $this->assertEquals($expected, $this->chain->getEventParams());
    }
}