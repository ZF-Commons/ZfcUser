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
     */
    protected function setUp()
    {
        $this->adapterChain = new AdapterChain();
        $this->adapterChain->setIdentity('identity');
        $this->adapterChain->setCredential('credential');
    }

    public function testAttachTriggersEvent()
    {
        $adapter1 = $this->getMockForAbstractClass('Zend\Authentication\Adapter\AbstractAdapter');
        
        $triggerCount = 0;
        $this->adapterChain->getEventManager()->attach('attach', function ($e) use ($adapter1, &$triggerCount) {
            $this->assertSame($this->adapterChain, $e->getTarget());
            $this->assertArrayHasKey('name', $e->getParams());
            $this->assertEquals('adapter1', $e->getParam('name'));
            $this->assertArrayHasKey('adapter', $e->getParams());
            $this->assertSame($adapter1, $e->getParam('adapter'));
            $this->assertArrayHasKey('priority', $e->getParams());
            $this->assertEquals(29, $e->getParam('priority'));
            $triggerCount++;
        });

        $this->adapterChain->attach('adapter1', $adapter1, 29);
        $this->assertEquals(1, $triggerCount);
    }

    public function testAuthenticateWithNoAdaptersReturnsUncategorizedFailure()
    {
        $result = $this->adapterChain->authenticate();

        $this->assertInstanceOf('Zend\Authentication\Result', $result);
        $this->assertEquals(Result::FAILURE_UNCATEGORIZED, $result->getCode());
        $this->assertEquals($result->getIdentity(), null);
        $this->assertEquals($result->getMessages(), array());
    }

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
    
    public function testAuthenticateTriggersEventsOnFailure()
    {
        $em = $this->adapterChain->getEventManager();
        
        $triggerCount['pre'] = 0;
        $em->attach('authenticate.pre', function ($e) use (&$triggerCount) {
            $this->assertSame($this->adapterChain, $e->getTarget());
            $this->assertEmpty($e->getParams());
            $triggerCount['pre']++;
        });
        
        $triggerCount['success'] = 0;
        $em->attach('authenticate.success', function ($e) use (&$triggerCount) {
            $this->assertSame($this->adapterChain, $e->getTarget());
            $this->assertArrayHasKey('adapter', $e->getParams());
            $this->assertArrayHasKey('result', $e->getParams());
            $triggerCount['success']++;
        });
                
        $triggerCount['failure'] = 0;
        $em->attach('authenticate.failure', function ($e) use (&$triggerCount) {
            $this->assertSame($this->adapterChain, $e->getTarget());
            $this->assertArrayHasKey('result', $e->getParams());
            $triggerCount['failure']++;
        });
        
        $this->testAuthenticateWithZeroAdapterMatchesReturnsLastAdapterResult();
        $this->assertEquals(1, $triggerCount['pre']);
        $this->assertEquals(0, $triggerCount['success']);
        $this->assertEquals(1, $triggerCount['failure']);
    }
    
    /**
     * Also enforces FIFO behavior of AdapterChain (If LIFO, adapter2 would execute first)
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

    public function testAuthenticateTriggersEventsOnSuccess()
    {
        $em = $this->adapterChain->getEventManager();
        
        $triggerCount['pre'] = 0;
        $em->attach('authenticate.pre', function ($e) use (&$triggerCount) {
            $this->assertSame($this->adapterChain, $e->getTarget());
            $this->assertEmpty($e->getParams());
            $triggerCount['pre']++;
        });
        
        $triggerCount['success'] = 0;
        $em->attach('authenticate.success', function ($e) use (&$triggerCount) {
            $this->assertSame($this->adapterChain, $e->getTarget());
            $this->assertArrayHasKey('adapter', $e->getParams());
            $this->assertArrayHasKey('result', $e->getParams());
            $triggerCount['success']++;
        });
                
        $triggerCount['failure'] = 0;
        $em->attach('authenticate.failure', function ($e) use (&$triggerCount) {
            $this->assertSame($this->adapterChain, $e->getTarget());
            $this->assertArrayHasKey('result', $e->getParams());
            $triggerCount['failure']++;
        });
        
        $this->testAuthenticateWithAdapterMatchReturnsSuccessfulAdapterResult();
        $this->assertEquals(1, $triggerCount['pre']);
        $this->assertEquals(1, $triggerCount['success']);
        $this->assertEquals(0, $triggerCount['failure']);
    }
}
