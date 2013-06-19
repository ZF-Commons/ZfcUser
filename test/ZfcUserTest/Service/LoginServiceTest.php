<?php

namespace ZfcUserTest\Service;

use ZfcUser\Form\LoginForm;
use ZfcUser\Options\ModuleOptions;
use ZfcUser\Service\LoginService;
use ZfcUserTest\Asset\LoginListener;

class LoginServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LoginForm
     */
    protected $form;

    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @var LoginService
     */
    protected $service;

    public function setUp()
    {
        $this->service = new LoginService();
    }

    /**
     * @covers \ZfcUser\Service\LoginService::login
     */
    public function testLogin()
    {
        $result = $this->service->login(array('identity' => 'test', 'credential' => 'test'));
        $this->assertInstanceOf('Zend\Authentication\Result', $result);
    }

    /**
     * @covers \ZfcUser\Service\LoginService::logout
     */
    public function testLogout()
    {
        $auth = $this->service->getAuthenticationService();
        $auth->getStorage()->write(array());

        $this->service->logout();
        $this->assertNull($auth->getStorage()->read());
    }

    /**
     * @covers \ZfcUser\Service\LoginService::getAdapterChain
     * @covers \ZfcUser\Service\LoginService::setAdapterChain
     */
    public function testChainIsLazyLoaded()
    {
        $this->assertInstanceOf(
            'ZfcUser\Authentication\AdapterChain',
            $this->service->getAdapterChain()
        );
    }

    /**
     * @covers \ZfcUser\Service\LoginService::getAuthenticationService
     * @covers \ZfcUser\Service\LoginService::setAuthenticationService
     */
    public function testAuthServiceIsLazyLoaded()
    {
        $this->assertInstanceOf(
            'Zend\Authentication\AuthenticationService',
            $this->service->getAuthenticationService()
        );
    }

    /**
     * @covers \ZfcUser\Service\LoginService::setLoginForm
     * @covers \ZfcUser\Service\LoginService::getLoginForm
     */
    public function testSetLoginFormFromListenerOnlyTriggersOnce()
    {
        $this->service->registerPlugin(new LoginListener());
        $form = $this->service->getLoginForm();
        $this->assertCount(4, $form->getElements());

        $form = $this->service->getLoginForm();
        $this->assertCount(4, $form);
    }

    /**
     * @covers \ZfcUser\Service\LoginService::getLoginForm
     */
    public function testGetLoginForm()
    {
        $this->assertInstanceOf('ZfcUser\Form\LoginForm', $this->service->getLoginForm());
    }
}
