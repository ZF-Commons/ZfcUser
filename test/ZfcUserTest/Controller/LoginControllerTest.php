<?php

namespace ZfcUserTest\Controller;

use ZfcUser\Form\LoginForm;
use ZfcUser\Options\ModuleOptions;
use ZfcUserTest\Asset\User;
use Zend\Authentication\Result;
use Zend\Http\Response;

class LoginControllerTest extends AbstractControllerTestCase
{
    /**
     * @covers \ZfcUser\Controller\LoginController::logoutAction
     */
    public function testLogoutAction()
    {
        $this->loginUser();

        $this->dispatch('/user/logout');
        $this->assertControllerName('ZfcUser\Controller\LoginController');
        $this->assertActionName('logout');
        $this->assertRedirectTo('/user/login');

        $this->assertNull($this->getAuth()->getIdentity());
    }

    /**
     * @covers \ZfcUser\Controller\LoginController::loginAction
     */
    public function testLoginActionRedirectsWhenLoggedIn()
    {
        $this->loginUser();

        $this->dispatch('/user/login');
        $this->assertControllerName('ZfcUser\Controller\LoginController');
        $this->assertActionName('login');
        $this->assertRedirectTo('/user');
    }

    /**
     * @covers \ZfcUser\Controller\LoginController::loginAction
     */
    public function testLoginActionDoesNotRedirect()
    {
        $this->logoutUser();

        $this->dispatch('/user/login');
        $this->assertControllerName('ZfcUser\Controller\LoginController');
        $this->assertActionName('login');
        $this->assertNotRedirect();
    }

    /**
     * @covers \ZfcUser\Controller\LoginController::loginAction
     */
    public function testLoginActionPrgResponse()
    {
        $this->logoutUser();

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        $request->setMethod('POST');

        $this->dispatch('/user/login');
        $this->assertRedirectTo('/user/login');
    }

    /**
     * @covers \ZfcUser\Controller\LoginController::loginAction
     */
    public function testLoginActionWithFalsePrg()
    {
        $this->logoutUser();

        $container = $this->getController('ZfcUser\Controller\LoginController')->plugin('prg')->getSessionContainer();
        $container->post = array(
            'identity'   => 'identity',
            'credential' => 'credential'
        );

        $this->dispatch('/user/login');
        $this->assertNotRedirect();
    }

    /**
     * @covers \ZfcUser\Controller\LoginController::loginAction
     */
    public function testValidLogin()
    {
        $this->logoutUser();

        $container = $this->getController('ZfcUser\Controller\LoginController')->plugin('prg')->getSessionContainer();
        $container->post = array(
            'identity'   => 'identity',
            'credential' => 'credential'
        );

        $this->getMockService()
             ->expects($this->once())
             ->method('login')
             ->will($this->returnValue(new Result(Result::SUCCESS, new User(), array())));

        $this->dispatch('/user/login');
        $this->assertRedirect('/user');
    }

    /**
     * @covers \ZfcUser\Controller\LoginController::getLoginService
     * @covers \ZfcUser\Controller\LoginController::setLoginService
     */
    public function testGetRegisterService()
    {
        $controller = $this->getController('ZfcUser\Controller\LoginController');
        $this->assertInstanceOf('ZfcUser\Service\LoginService', $controller->getLoginService());
    }

    protected function getMockService()
    {
        /** @var \ZfcUser\Controller\LoginController $controller */
        $controller   = $this->getController('ZfcUser\Controller\LoginController');
        $loginService = $this->getMock('ZfcUser\Service\LoginService');

        $controller->setLoginService($loginService);

        return $loginService;
    }
}