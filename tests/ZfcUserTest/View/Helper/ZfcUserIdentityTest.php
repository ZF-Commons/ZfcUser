<?php

namespace ZfcUserTest\View\Helper;

use ZfcUser\View\Helper\ZfcUserIdentity as ViewHelper;

class ZfcUserIdentityTest extends \PHPUnit_Framework_TestCase
{
    protected $helper;

    protected $authService;

    public function setUp()
    {
        $helper = new ViewHelper;
        $this->helper = $helper;

        $authService = $this->getMock('Zend\Authentication\AuthenticationService');
        $this->authService = $authService;

        $helper->setAuthService($authService);
    }

    /**
     * @covers ZfcUser\View\Helper\ZfcUserIdentity::__invoke
     */
    public function testInvokeWithIdentity()
    {
        $this->authService->expects($this->once())
                          ->method('hasIdentity')
                          ->will($this->returnValue(true));
        $this->authService->expects($this->once())
                          ->method('getIdentity')
                          ->will($this->returnValue('zfcUser'));

        $result = $this->helper->__invoke();

        $this->assertEquals('zfcUser', $result);
    }

    /**
     * @covers ZfcUser\View\Helper\ZfcUserIdentity::__invoke
     */
    public function testInvokeWithoutIdentity()
    {
        $this->authService->expects($this->once())
                          ->method('hasIdentity')
                          ->will($this->returnValue(false));

        $result = $this->helper->__invoke();

        $this->assertFalse($result);
    }

    /**
     * @covers ZfcUser\View\Helper\ZfcUserIdentity::setAuthService
     * @covers ZfcUser\View\Helper\ZfcUserIdentity::getAuthService
     */
    public function testSetGetAuthService()
    {
        //We set the authservice in setUp, so we dont have to set it again
        $this->assertSame($this->authService, $this->helper->getAuthService());
    }
}
