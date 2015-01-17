<?php

namespace ZfcUserTest\Authentication\Adapter;

use ZfcUser\Authentication\Adapter\AdapterChain;
use Zend\Authentication\Result;

class AdapterChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The object to be tested.
     *
     * @var AdapterChain
     */
    protected $adapterChain;

    /**
     * Prepare the objects to be tested.
     * @covers ZfcUser\Authentication\Adapter\AdapterChain::__construct
     */
    protected function setUp()
    {
        $this->adapterChain = new AdapterChain();
        $this->adapterChain->setIdentity('identity');
        $this->adapterChain->setCredential('credential');
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\AdapterChain::authenticate
     */
    public function testAuthenticateWithNoAdaptersReturnsUncategorizedFailure()
    {
        $result = $this->adapterChain->authenticate();

        $this->assertInstanceOf('Zend\Authentication\Result', $result);
        $this->assertEquals(Result::FAILURE_UNCATEGORIZED, $result->getCode());
        $this->assertEquals($result->getIdentity(), null);
        $this->assertEquals($result->getMessages(), array());
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\AdapterChain::authenticate
     * @covers ZfcUser\Authentication\Adapter\AdapterChain::attach
     */
    public function testAuthenticateWithZeroAdapterMatchesReturnsLastAdapterResult()
    {
        $result1 = new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null);
        $adapter1 = $this->getMockForAbstractClass('Zend\Authentication\Adapter\AbstractAdapter');
        $adapter1->expects($this->once())->method('authenticate')->will($this->returnValue($result1));
        $this->adapterChain->attach('adapter1', $adapter1);

        $result2 = new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null);
        $adapter2 = $this->getMockForAbstractClass('Zend\Authentication\Adapter\AbstractAdapter');
        $adapter2->expects($this->once())->method('authenticate')->will($this->returnValue($result2));
        $this->adapterChain->attach('adapter2', $adapter2);
        
        $result = $this->adapterChain->authenticate();

        $this->assertSame($result2, $result);
        $this->assertEquals(Result::FAILURE_IDENTITY_NOT_FOUND, $result->getCode());
        $this->assertEquals($result->getIdentity(), null);
        $this->assertEquals($result->getMessages(), array());
    }
    
    /**
     * Also enforces FIFO behavior of AdapterChain (If LIFO, adapter2 would execute first)
     * 
     * @covers ZfcUser\Authentication\Adapter\AdapterChain::authenticate
     * @covers ZfcUser\Authentication\Adapter\AdapterChain::attach
     */
    public function testAuthenticateWithAdapterMatchReturnsSuccessfulAdapterResult()
    {
        $result1 = new Result(Result::SUCCESS, $this->adapterChain->getIdentity());
        $adapter1 = $this->getMockForAbstractClass('Zend\Authentication\Adapter\AbstractAdapter');
        $adapter1->expects($this->once())->method('authenticate')->will($this->returnValue($result1));
        $this->adapterChain->attach('adapter1', $adapter1);

        $adapter2 = $this->getMockForAbstractClass('Zend\Authentication\Adapter\AbstractAdapter');
        $adapter2->expects($this->never())->method('authenticate');
        $this->adapterChain->attach('adapter2', $adapter2);
        
        $result = $this->adapterChain->authenticate();

        $this->assertSame($result1, $result);
        $this->assertEquals(Result::SUCCESS, $result->getCode());
        $this->assertEquals($result->getIdentity(), $this->adapterChain->getIdentity());
        $this->assertEquals($result->getMessages(), array());
    }
}
