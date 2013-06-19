<?php

namespace ZfcUserTest\Controller;

use ZfcUser\Form\RegisterForm;
use ZfcUser\Options\ModuleOptions;
use ZfcUserTest\Asset\User;

class RegisterControllerTest extends AbstractControllerTestCase
{
    /**
     * @covers \ZfcUser\Controller\RegisterController::registerAction
     */
    public function testRegisterActionRedirectsWhenLoggedIn()
    {
        $this->loginUser();

        $this->dispatch('/user/register');
        $this->assertControllerName('ZfcUser\Controller\RegisterController');
        $this->assertActionName('register');
        $this->assertRedirectTo('/user');
    }

    /**
     * @covers \ZfcUser\Controller\RegisterController::registerAction
     */
    public function testRegisterActionDoesNotRedirect()
    {
        $this->logoutUser();

        $this->dispatch('/user/register');
        $this->assertControllerName('ZfcUser\Controller\RegisterController');
        $this->assertActionName('register');
        $this->assertNotRedirect();
    }

    /**
     * @covers \ZfcUser\Controller\RegisterController::registerAction
     */
    public function testRegisterActionPrgResponse()
    {
        $this->logoutUser();

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        $request->setMethod('POST');

        $this->dispatch('/user/register');
        $this->assertRedirectTo('/user/register');
    }

    /**
     * @covers \ZfcUser\Controller\RegisterController::registerAction
     */
    public function testRegisterActionWithFalsePrg()
    {
        $this->logoutUser();

        $controller = $this->getController('ZfcUser\Controller\RegisterController');
        $container  = $controller->plugin('prg')->getSessionContainer();
        $container->post = array(
            'identity'   => 'identity',
            'credential' => 'credential'
        );

        $this->dispatch('/user/register');
        $this->assertNotRedirect();
    }

    /**
     * @covers \ZfcUser\Controller\RegisterController::registerAction
     */
    public function testValidRegistration()
    {
        $this->logoutUser();

        $controller = $this->getController('ZfcUser\Controller\RegisterController');
        $container  = $controller->plugin('prg')->getSessionContainer();
        $container->post = array(
            'identity'   => 'identity',
            'credential' => 'credential'
        );

        $this->getMockService()
            ->expects($this->once())
            ->method('register')
            ->will($this->returnValue(new User()));

        $this->dispatch('/user/register');
        $this->assertRedirect('/user');
    }

    /**
     * @covers \ZfcUser\Controller\RegisterController::registerAction
     */
    public function testInvalidRegistration()
    {
        $this->logoutUser();

        $controller = $this->getController('ZfcUser\Controller\RegisterController');
        $container  = $controller->plugin('prg')->getSessionContainer();
        $container->post = array(
            'identity'   => 'identity',
            'credential' => 'credential'
        );

        $this->getMockService()
             ->expects($this->once())
             ->method('register')
             ->will($this->returnValue(null));

        $this->dispatch('/user/register');
        $this->assertNotRedirect();
    }

    /**
     * @covers \ZfcUser\Controller\RegisterController::getRegisterService
     * @covers \ZfcUser\Controller\RegisterController::setRegisterService
     */
    public function testGetRegisterService()
    {
        $controller = $this->getController('ZfcUser\Controller\RegisterController');
        $this->assertInstanceOf('ZfcUser\Service\RegisterService', $controller->getRegisterService());
    }

    protected function getMockService()
    {
        /** @var \ZfcUser\Controller\RegisterController $controller */
        $controller = $this->getController('ZfcUser\Controller\RegisterController');
        $service    = $this->getMock(
            'ZfcUser\Service\RegisterService',
            array(),
            array(
                new ModuleOptions()
            )
        );

        $controller->setRegisterService($service);
        return $service;
    }
}