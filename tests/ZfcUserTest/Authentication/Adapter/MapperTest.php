<?php

namespace ZfcUserTest\Authentication\Adapter;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use ZfcUser\Authentication\Adapter\Mapper;
use Zend\Authentication\Result;

class MapperTest extends TestCase
{
    /**
     * The object to be tested.
     *
     * @var Db
     */
    protected $adapter;

    /**
     * Mock of Mapper.
     *
     * @var MockObject
     */
    protected $mapper;

    /**
     * @var MockObject
     */
    protected $processor;

    /**
     * Mock of User.
     *
     * @var MockObject
     */
    protected $user;

    protected function setUp()
    {
        $this->mapper    = $this->getMockForAbstractClass(
            'ZfcUser\Mapper\UserInterface'
        );
        $this->user      = $this->getMockForAbstractClass(
            'ZfcUser\Entity\UserInterface'
        );
        $this->processor = $this->getMock('Zend\Crypt\Password\PasswordInterface');

        $this->adapter = new Mapper($this->mapper, 'findByUsername', $this->processor);
        $this->adapter->setIdentity('identity');
        $this->adapter->setCredential('credential');
    }

    public function testAuthenticateWhenNoUserEntityFound()
    {
        $this->mapper->expects($this->once())
                     ->method('findByUsername')
                     ->with('identity')
                     ->will($this->returnValue(null));
        
        $result = $this->adapter->authenticate();
        $this->assertInstanceOf('Zend\Authentication\Result', $result);
        $this->assertEquals(Result::FAILURE_IDENTITY_NOT_FOUND, $result->getCode());
        $this->assertCount(1, $result->getMessages());
    }

    public function testAuthenticateWhenUserEntityFoundButPasswordIncorrect()
    {
        $this->user->expects($this->once())
                   ->method('getPassword')
                   ->will($this->returnValue('notthecredential'));
        $this->mapper->expects($this->once())
                     ->method('findByUsername')
                     ->with('identity')
                     ->will($this->returnValue($this->user));
        $this->processor->expects($this->once())
                        ->method('verify')
                        ->with('credential', 'notthecredential')
                        ->will($this->returnValue(false));
        
        $result = $this->adapter->authenticate();
        $this->assertInstanceOf('Zend\Authentication\Result', $result);
        $this->assertEquals(Result::FAILURE_CREDENTIAL_INVALID, $result->getCode());
        $this->assertCount(1, $result->getMessages());
    }

    public function testAuthenticateSuccessful()
    {
        $this->user->expects($this->once())
                   ->method('getPassword')
                   ->will($this->returnValue('credential'));
        $this->mapper->expects($this->once())
                     ->method('findByUsername')
                     ->with('identity')
                     ->will($this->returnValue($this->user));
        $this->processor->expects($this->once())
                        ->method('verify')
                        ->with('credential', 'credential')
                        ->will($this->returnValue(true));
        
        $result = $this->adapter->authenticate();
        $this->assertInstanceOf('Zend\Authentication\Result', $result);
        $this->assertEquals(Result::SUCCESS, $result->getCode());
        $this->assertCount(1, $result->getMessages());
    }
}
