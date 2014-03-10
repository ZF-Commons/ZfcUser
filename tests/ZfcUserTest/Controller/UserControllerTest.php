<?php

namespace ZfcUserTest\Controller;

use ZfcUser\Controller\UserController as Controller;

class UserControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ZfcUser\Controller\UserController $controller
     */
    protected $controller;

    protected $pluginManager;

    protected $zfcUserAuthenticationPlugin;

    protected $options;

    public function setUp()
    {
        $controller = new Controller;
        $this->controller = $controller;

        $zfcUserAuthenticationPlugin = $this->getMock('ZfcUser\Controller\Plugin\ZfcUserAuthentication');
        $this->zfcUserAuthenticationPlugin = $zfcUserAuthenticationPlugin;

        $pluginManager = $this->getMock('Zend\Mvc\Controller\PluginManager');
        $this->pluginManager = $pluginManager;

        $options = $this->getMock('ZfcUser\Options\ModuleOptions');
        $this->options = $options;

        $controller->setPluginManager($pluginManager);
        $controller->setOptions($options);
    }

    public function testIndexActionLoggedIn()
    {
        $this->zfcUserAuthenticationPlugin->expects($this->once())
                                          ->method('hasIdentity')
                                          ->will($this->returnValue(false));

        $redirect = $this->getMock('Zend\Mvc\Controller\Plugin\Redirect');
        $redirect->expects($this->once())
                 ->method('toRoute')
                 ->with(\ZfcUser\Controller\UserController::ROUTE_LOGIN);

        $this->pluginManager->expects($this->at(1))
             ->method('get')
             ->with('zfcUserAuthentication')
             ->will($this->returnValue($this->zfcUserAuthenticationPlugin));
        $this->pluginManager->expects($this->at(3))
             ->method('get')
             ->with('redirect')
             ->will($this->returnValue($redirect));

        $this->controller->indexAction();
    }

    public function testIndexActionNotLoggedIn()
    {
        $this->zfcUserAuthenticationPlugin->expects($this->once())
             ->method('hasIdentity')
             ->will($this->returnValue(true));

        $this->pluginManager->expects($this->at(1))
             ->method('get')
             ->with('zfcUserAuthentication')
             ->will($this->returnValue($this->zfcUserAuthenticationPlugin));

        $result = $this->controller->indexAction();

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }

    public function testLoginActionLoggedIn()
    {
        $this->zfcUserAuthenticationPlugin->expects($this->once())
             ->method('hasIdentity')
             ->will($this->returnValue(true));
        $plugin = $this->zfcUserAuthenticationPlugin;

        $this->options->expects($this->once())
                      ->method('getLoginRedirectRoute')
                      ->will($this->returnValue('zfcUserRoute'));

        $redirect = $this->getMock('Zend\Mvc\Controller\Plugin\Redirect');
        $redirect->expects($this->once())
                 ->method('toRoute')
                 ->with('zfcUserRoute');

        $this->pluginManager->expects($this->any())
             ->method('get')
             ->will($this->returnCallback(function() use ($plugin, $redirect){
                    $args = func_get_args();
                    if (isset($args[0])) {
                        switch ($args[0]) {
                            case 'zfcUserAuthentication':
                                return $plugin;
                                break;
                            case 'redirect':
                                return $redirect;
                                break;
                        }
                    }
                })
             );

        $this->controller->loginAction();
    }

    public function testLoginActionValidFormRedirectFalse()
    {
        $adapter = $this->getMock('ZfcUser\Authentication\Adapter\AdapterChain');
        $adapter->expects($this->once())
                ->method('resetAdapters');

        $service = $this->getMock('Zend\Authentication\AuthenticationService');
        $service->expects($this->once())
                ->method('clearIdentity');

        $this->zfcUserAuthenticationPlugin->expects($this->once())
                                          ->method('hasIdentity')
                                          ->will($this->returnValue(false));
        $this->zfcUserAuthenticationPlugin->expects($this->once())
                                          ->method('getAuthAdapter')
                                          ->will($this->returnValue($adapter));
        $this->zfcUserAuthenticationPlugin->expects($this->once())
                                          ->method('getAuthService')
                                          ->will($this->returnValue($service));
        $plugin = $this->zfcUserAuthenticationPlugin;

        $flashMessenger = $this->getMock('Zend\Mvc\Controller\Plugin\FlashMessenger');
        $flashMessenger->expects($this->once())
                       ->method('setNamespace')
                       ->with('zfcuser-login-form')
                       ->will($this->returnSelf());

        $forwardPlugin = $this->getMockBuilder('Zend\Mvc\Controller\Plugin\Forward')
                              ->disableOriginalConstructor()
                              ->getMock();
        $forwardPlugin->expects($this->once())
                      ->method('dispatch')
                      ->with(\ZfcUser\Controller\UserController::CONTROLLER_NAME, array('action' => 'authenticate'));

        $this->pluginManager->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function() use ($plugin, $flashMessenger, $forwardPlugin){
                    $args = func_get_args();
                    if (isset($args[0])) {
                        switch ($args[0]) {
                            case 'zfcUserAuthentication':
                                return $plugin;
                                break;
                            case 'flashMessenger':
                                return $flashMessenger;
                                break;
                            case 'forward':
                                return $forwardPlugin;
                                break;
                        }
                    }
                })
            );

        $postArray = array('some', 'data');
        $request = $this->getMock('Zend\Http\Request');
        $request->expects($this->once())
                ->method('isPost')
                ->will($this->returnValue(true));
        $request->expects($this->once())
                ->method('getPost')
                ->will($this->returnValue($postArray));

        $form = $this->getMockBuilder('ZfcUser\Form\Login')
                     ->disableOriginalConstructor()
                     ->getMock();
        $form->expects($this->once())
             ->method('isValid')
             ->will($this->returnValue(true));
        $form->expects($this->once())
             ->method('setData')
             ->with($postArray);

        $this->options->expects($this->once())
                      ->method('getUseRedirectParameterIfPresent')
                      ->will($this->returnValue(false));

        $reflectionClass = new \ReflectionClass('ZfcUser\Controller\UserController');
        $reflectionProperty = $reflectionClass->getProperty('request');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->controller, $request);

        $this->controller->setLoginForm($form);
        $this->controller->loginAction();
    }

    public function testLoginActionIsNotPost()
    {
        $this->zfcUserAuthenticationPlugin->expects($this->once())
             ->method('hasIdentity')
             ->will($this->returnValue(false));
        $plugin = $this->zfcUserAuthenticationPlugin;

        $flashMessenger = $this->getMock('Zend\Mvc\Controller\Plugin\FlashMessenger');
        $flashMessenger->expects($this->once())
                       ->method('setNamespace')
                       ->with('zfcuser-login-form')
                       ->will($this->returnSelf());

        $this->pluginManager->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function() use ($plugin, $flashMessenger){
                    $args = func_get_args();
                    if (isset($args[0])) {
                        switch ($args[0]) {
                            case 'zfcUserAuthentication':
                                return $plugin;
                                break;
                            case 'flashMessenger':
                                return $flashMessenger;
                                break;
                        }
                    }
                })
            );

        $request = $this->getMock('Zend\Http\Request');
        $request->expects($this->once())
                ->method('isPost')
                ->will($this->returnValue(false));

        $form = $this->getMockBuilder('ZfcUser\Form\Login')
                     ->disableOriginalConstructor()
                     ->getMock();
        $form->expects($this->never())
             ->method('isValid');

        $this->options->expects($this->once())
            ->method('getUseRedirectParameterIfPresent')
            ->will($this->returnValue(false));

        $reflectionClass = new \ReflectionClass('ZfcUser\Controller\UserController');
        $reflectionProperty = $reflectionClass->getProperty('request');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->controller, $request);

        $this->controller->setLoginForm($form);
        $result = $this->controller->loginAction();

        $this->assertArrayHasKey('loginForm', $result);
        $this->assertArrayHasKey('redirect', $result);
        $this->assertArrayHasKey('enableRegistration', $result);
    }

    public function testLogoutAction()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testAuthenticateAction()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testRegisterAction()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testChangepasswordAction()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testChangeEmailAction()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetUserService()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testSetUserService()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetRegisterForm()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testSetRegisterForm()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetLoginForm()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testSetLoginForm()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetChangePasswordForm()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testSetChangePasswordForm()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testSetOptions()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetOptions()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetChangeEmailForm()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testSetChangeEmailForm()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
