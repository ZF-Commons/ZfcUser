<?php

namespace ZfcUserTest\View\Helper;

use ZfcUser\View\Helper\ZfcUserDisplayName as ViewHelper;

class ZfcUserDisplayNameTest extends \PHPUnit_Framework_TestCase
{
    protected $helper;

    protected $authService;

    protected $user;

    public function setUp()
    {
        $helper = new ViewHelper;
        $this->helper = $helper;

        $authService = $this->getMock('Zend\Authentication\AuthenticationService');
        $this->authService = $authService;

        $user = $this->getMock('ZfcUser\Entity\User');
        $this->user = $user;

        $helper->setAuthService($authService);
    }

    /**
     * @covers ZfcUser\View\Helper\ZfcUserDisplayName::__invoke
     */
    public function testInvokeWithoutUserAndNotLoggedIn()
    {
        $this->authService->expects($this->once())
                          ->method('hasIdentity')
                          ->will($this->returnValue(false));

        $result = $this->helper->__invoke(null);

        $this->assertFalse($result);
    }

    /**
     * @covers ZfcUser\View\Helper\ZfcUserDisplayName::__invoke
     * @expectedException ZfcUser\Exception\DomainException
     */
    public function testInvokeWithoutUserButLoggedInWithWrongUserObject()
    {
        $this->authService->expects($this->once())
                          ->method('hasIdentity')
                          ->will($this->returnValue(true));
        $this->authService->expects($this->once())
                          ->method('getIdentity')
                          ->will($this->returnValue(new \StdClass));

        $this->helper->__invoke(null);
    }

    /**
     * @covers ZfcUser\View\Helper\ZfcUserDisplayName::__invoke
     */
    public function testInvokeWithoutUserButLoggedInWithDisplayName()
    {
        $this->user->expects($this->once())
                   ->method('getDisplayName')
                   ->will($this->returnValue('zfcUser'));

        $this->authService->expects($this->once())
                          ->method('hasIdentity')
                          ->will($this->returnValue(true));
        $this->authService->expects($this->once())
                          ->method('getIdentity')
                          ->will($this->returnValue($this->user));

        $result = $this->helper->__invoke(null);

        $this->assertEquals('zfcUser', $result);
    }

    /**
     * @covers ZfcUser\View\Helper\ZfcUserDisplayName::__invoke
     */
    public function testInvokeWithoutUserButLoggedInWithoutDisplayNameButWithUsername()
    {
        $this->user->expects($this->once())
                   ->method('getDisplayName')
                   ->will($this->returnValue(null));
        $this->user->expects($this->once())
                   ->method('getUsername')
                   ->will($this->returnValue('zfcUser'));

        $this->authService->expects($this->once())
                          ->method('hasIdentity')
                          ->will($this->returnValue(true));
        $this->authService->expects($this->once())
                          ->method('getIdentity')
                          ->will($this->returnValue($this->user));

        $result = $this->helper->__invoke(null);

        $this->assertEquals('zfcUser', $result);
    }

    /**
     * @covers ZfcUser\View\Helper\ZfcUserDisplayName::__invoke
     */
    public function testInvokeWithoutUserButLoggedInWithoutDisplayNameAndWithOutUsernameButWithEmail()
    {
        $this->user->expects($this->once())
                   ->method('getDisplayName')
                   ->will($this->returnValue(null));
        $this->user->expects($this->once())
                   ->method('getUsername')
                   ->will($this->returnValue(null));
        $this->user->expects($this->once())
                   ->method('getEmail')
                   ->will($this->returnValue('zfcUser@zfcUser.com'));

        $this->authService->expects($this->once())
                          ->method('hasIdentity')
                          ->will($this->returnValue(true));
        $this->authService->expects($this->once())
                          ->method('getIdentity')
                          ->will($this->returnValue($this->user));

        $result = $this->helper->__invoke(null);

        $this->assertEquals('zfcUser', $result);
    }

    /**
     * @covers ZfcUser\View\Helper\ZfcUserDisplayName::setAuthService
     * @covers ZfcUser\View\Helper\ZfcUserDisplayName::getAuthService
     */
    public function testSetGetAuthService()
    {
        // We set the authservice in setUp, so we dont have to set it again
        $this->assertSame($this->authService, $this->helper->getAuthService());
    }
}
