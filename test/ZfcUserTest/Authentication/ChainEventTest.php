<?php

namespace ZfcUserTest\Form;

use Zend\Authentication\Result;
use ZfcUser\Authentication;
use ZfcUser\Authentication\AdapterChain;
use ZfcUser\Authentication\ChainEvent;

class ChainEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ChainEvent
     */
    protected $event;

    public function setUp()
    {
        $this->event = new ChainEvent(new AdapterChain());
    }

    /**
     * @covers \ZfcUser\Authentication\ChainEvent::__construct
     * @covers \ZfcUser\Authentication\ChainEvent::addMessages
     * @covers \ZfcUser\Authentication\ChainEvent::setMessages
     * @covers \ZfcUser\Authentication\ChainEvent::getMessages
     * @covers \ZfcUser\Authentication\ChainEvent::clearMessages
     */
    public function testMessages()
    {
        $original = array('foo', 'bar');
        $expected = array('baz', 'foo', 'bar');

        $this->event->setMessages($original);
        $this->event->addMessages(array('baz'));
        $this->assertEquals($expected, $this->event->getMessages());

        $this->event->clearMessages();
        $this->assertEmpty($this->event->getMessages());
    }

    /**
     * @covers \ZfcUser\Authentication\ChainEvent::setIdentity
     * @covers \ZfcUser\Authentication\ChainEvent::getIdentity
     */
    public function testIdentity()
    {
        $this->event->setIdentity('foo');
        $this->assertEquals('foo', $this->event->getIdentity());
    }

    /**
     * @covers \ZfcUser\Authentication\ChainEvent::setCode
     * @covers \ZfcUser\Authentication\ChainEvent::getCode
     */
    public function testCode()
    {
        $this->event->setCode(Result::SUCCESS);
        $this->assertEquals(Result::SUCCESS, $this->event->getCode());
    }
}