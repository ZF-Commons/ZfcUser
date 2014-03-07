<?php

namespace ZfcUserTest\Controller\Plugin;

use ZfcUser\Controller\Plugin\ZfcUserAuthentication as Plugin;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\AdapterInterface;
use ZfcUser\Authentication\Adapter\AdapterChain;

class ZfcUserAuthenticationTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Plugin
     */
    protected $SUT;

    /**
     *
     * @var AuthenticationService
     */
    protected $mockedAuthenticationService;

    /**
     *
     * @var AdapterChain
     */
    protected $mockedAuthenticationAdapter;

    public function setUp()
    {
        $this->SUT = new Plugin();
        $this->mockedAuthenticationService = $this->getMock('Zend\Authentication\AuthenticationService');
        $this->mockedAuthenticationAdapter = $this->getMockForAbstractClass('\ZfcUser\Authentication\Adapter\AdapterChain');
    }


    /**
     * @covers ZfcUser\Controller\Plugin\ZfcUserAuthentication::hasIdentity
     * @covers ZfcUser\Controller\Plugin\ZfcUserAuthentication::getIdentity
     */
    public function testGetAndHasIdentity()
    {
        $this->SUT->setAuthService($this->mockedAuthenticationService);

        $callbackIndex = 0;
        $callback = function () use (&$callbackIndex) {
            $callbackIndex++;
            return (bool) ($callbackIndex % 2);
        };

        $this->mockedAuthenticationService->expects($this->any())
                                          ->method('hasIdentity')
                                          ->will($this->returnCallback($callback));

        $this->mockedAuthenticationService->expects($this->any())
                                          ->method('getIdentity')
                                          ->will($this->returnCallback($callback));

        $this->assertTrue($this->SUT->hasIdentity());
        $this->assertFalse($this->SUT->hasIdentity());
        $this->assertTrue($this->SUT->hasIdentity());

        $callbackIndex= 0;

        $this->assertTrue($this->SUT->getIdentity());
        $this->assertFalse($this->SUT->getIdentity());
        $this->assertTrue($this->SUT->getIdentity());
    }

    /**
     * @covers ZfcUser\Controller\Plugin\ZfcUserAuthentication::setAuthAdapter
     * @covers ZfcUser\Controller\Plugin\ZfcUserAuthentication::getAuthAdapter
     */
    public function testSetAndGetAuthAdapter()
    {
        $adapter1 = $this->mockedAuthenticationAdapter;
        $adapter2 = new AdapterChain();
        $this->SUT->setAuthAdapter($adapter1);

        $this->assertInstanceOf('\Zend\Authentication\Adapter\AdapterInterface', $this->SUT->getAuthAdapter());
        $this->assertSame($adapter1, $this->SUT->getAuthAdapter());

        $this->SUT->setAuthAdapter($adapter2);

        $this->assertInstanceOf('\Zend\Authentication\Adapter\AdapterInterface', $this->SUT->getAuthAdapter());
        $this->assertNotSame($adapter1, $this->SUT->getAuthAdapter());
        $this->assertSame($adapter2, $this->SUT->getAuthAdapter());
    }

    /**
     * @covers ZfcUser\Controller\Plugin\ZfcUserAuthentication::setAuthService
     * @covers ZfcUser\Controller\Plugin\ZfcUserAuthentication::getAuthService
     */
    public function testSetAndGetAuthService()
    {
        $service1 = new AuthenticationService();
        $service2 = new AuthenticationService();
        $this->SUT->setAuthService($service1);

        $this->assertInstanceOf('\Zend\Authentication\AuthenticationService', $this->SUT->getAuthService());
        $this->assertSame($service1, $this->SUT->getAuthService());

        $this->SUT->setAuthService($service2);

        $this->assertInstanceOf('\Zend\Authentication\AuthenticationService', $this->SUT->getAuthService());
        $this->assertNotSame($service1, $this->SUT->getAuthService());
        $this->assertSame($service2, $this->SUT->getAuthService());
    }
}
