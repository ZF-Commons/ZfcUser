<?php

namespace ZfcUserTest\Controller;

use ZfcUser\Controller\UserController as Controller;
use Zend\Http\Response;
use Zend\Stdlib\Parameters;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Service\User as UserService;
use Zend\Form\Form;
use ZfcUser\Options\ModuleOptions;

class UserControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \ZfcUser\Controller\UserController $controller
     */
    protected $controller;

    protected $pluginManager;

    public $pluginManagerPlugins = array();

    protected $zfcUserAuthenticationPlugin;

    protected $options;

    public function setUp()
    {
        $controller = new Controller;
        $this->controller = $controller;

        $this->zfcUserAuthenticationPlugin = $this->getMock('ZfcUser\Controller\Plugin\ZfcUserAuthentication');

        $pluginManager = $this->getMock('Zend\Mvc\Controller\PluginManager', array('get'));

        $pluginManager->expects($this->any())
                      ->method('get')
                      ->will($this->returnCallback(array($this, 'helperMockCallbackPluginManagerGet')));

        $this->pluginManager = $pluginManager;

        $options = $this->getMock('ZfcUser\Options\ModuleOptions');
        $this->options = $options;

        $controller->setPluginManager($pluginManager);
        $controller->setOptions($options);
    }

    public function setUpZfcUserAuthenticationPlugin($option)
    {
        if (array_key_exists('hasIdentity', $option)) {
            $return = (is_callable($option['hasIdentity']))
                ? $this->returnCallback($option['hasIdentity'])
                : $this->returnValue($option['hasIdentity']);
            $this->zfcUserAuthenticationPlugin->expects($this->any())
                 ->method('hasIdentity')
                 ->will($return);
        }

        if (array_key_exists('getAuthAdapter', $option)) {
            $return = (is_callable($option['getAuthAdapter']))
                ? $this->returnCallback($option['getAuthAdapter'])
                : $this->returnValue($option['getAuthAdapter']);

            $this->zfcUserAuthenticationPlugin->expects($this->any())
                 ->method('getAuthAdapter')
                 ->will($return);
        }

        if (array_key_exists('getAuthService', $option)) {
            $return = (is_callable($option['getAuthService']))
                ? $this->returnCallback($option['getAuthService'])
                : $this->returnValue($option['getAuthService']);

            $this->zfcUserAuthenticationPlugin->expects($this->any())
                 ->method('getAuthService')
                 ->will($return);
        }

        $this->pluginManagerPlugins['zfcUserAuthentication'] = $this->zfcUserAuthenticationPlugin;

        return $this->zfcUserAuthenticationPlugin;
    }

    public function testIndexActionNotLoggedIn()
    {
        $controller = $this->controller;
        $this->setUpZfcUserAuthenticationPlugin(array(
            'hasIdentity'=>false
        ));

        $response = new Response();

        $redirect = $this->getMock('Zend\Mvc\Controller\Plugin\Redirect', array('toRoute'));
        $redirect->expects($this->once())
                 ->method('toRoute')
                 ->with($controller::ROUTE_LOGIN)
                 ->will($this->returnValue($response));

        $this->pluginManagerPlugins['redirect']= $redirect;

        $result = $controller->indexAction();

        $this->assertInstanceOf('\Zend\Http\Response', $result);
        $this->assertSame($response, $result);
    }

    public function testIndexActionLoggedIn()
    {
        $controller = $this->controller;
        $this->setUpZfcUserAuthenticationPlugin(array(
            'hasIdentity'=>true
        ));

        $result = $controller->indexAction();

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }

    public function testLoginActionLoggedIn()
    {
        $this->setUpZfcUserAuthenticationPlugin(array(
            'hasIdentity'=>true
        ));
        $plugin = $this->zfcUserAuthenticationPlugin;

        $this->options->expects($this->once())
                      ->method('getLoginRedirectRoute')
                      ->will($this->returnValue('zfcUserRoute'));

        $response = new Response();
        $redirect = $this->getMock('Zend\Mvc\Controller\Plugin\Redirect', array('toRoute'));
        $redirect->expects($this->once())
                 ->method('toRoute')
                 ->with('zfcUserRoute')
                 ->will($this->returnValue($response));

        $this->pluginManagerPlugins['redirect']= $redirect;

        $this->controller->loginAction();
    }

    /**
     * @dataProvider providerTrueOrFalseX2
     */
    public function testLoginActionValidFormRedirectFalse($isValid, $wantRedirect)
    {
        $controller = $this->controller;
        $redirectUrl = 'http://localhost/redirect1';

        $plugin = $this->setUpZfcUserAuthenticationPlugin(array(
            'hasIdentity'=>false
        ));

        $flashMessenger = $this->getMock(
            'Zend\Mvc\Controller\Plugin\FlashMessenger'
        );
        $this->pluginManagerPlugins['flashMessenger']= $flashMessenger;

        $flashMessenger->expects($this->any())
                       ->method('setNamespace')
                       ->with('zfcuser-login-form')
                       ->will($this->returnSelf());

        $flashMessenger->expects($this->once())
                       ->method('getMessages')
                       ->will($this->returnValue(array()));

        $flashMessenger->expects($this->any())
                       ->method('addMessage')
                       ->will($this->returnSelf());

        $postArray = array('some', 'data');
        $request = $this->getMock('Zend\Http\Request');
        $request->expects($this->any())
                ->method('isPost')
                ->will($this->returnValue(true));
        $request->expects($this->any())
                ->method('getPost')
                ->will($this->returnValue($postArray));

        $this->helperMakePropertyAccessable($controller, 'request', $request);

        $form = $this->getMockBuilder('ZfcUser\Form\Login')
                     ->disableOriginalConstructor()
                     ->getMock();

        $form->expects($this->any())
             ->method('isValid')
             ->will($this->returnValue((bool) $isValid));


        $this->options->expects($this->any())
                      ->method('getUseRedirectParameterIfPresent')
                      ->will($this->returnValue((bool) $wantRedirect));
        if ($wantRedirect) {
            $params = new Parameters();
            $params->set('redirect', $redirectUrl);

            $request->expects($this->any())
                    ->method('getQuery')
                    ->will($this->returnValue($params));
        }

        if ($isValid) {
            $adapter = $this->getMock('ZfcUser\Authentication\Adapter\AdapterChain');
            $adapter->expects($this->once())
                    ->method('resetAdapters');

            $service = $this->getMock('Zend\Authentication\AuthenticationService');
            $service->expects($this->once())
                    ->method('clearIdentity');

            $plugin = $this->setUpZfcUserAuthenticationPlugin(array(
                'getAuthAdapter'=>$adapter,
                'getAuthService'=>$service
            ));

            $form->expects($this->once())
                 ->method('setData')
                 ->with($postArray);

            $expectedResult = new \stdClass();

            $forwardPlugin = $this->getMockBuilder('Zend\Mvc\Controller\Plugin\Forward')
                                  ->disableOriginalConstructor()
                                  ->getMock();
            $forwardPlugin->expects($this->once())
                          ->method('dispatch')
                          ->with($controller::CONTROLLER_NAME, array('action' => 'authenticate'))
                          ->will($this->returnValue($expectedResult));

            $this->pluginManagerPlugins['forward']= $forwardPlugin;

        } else {
            $response = new Response();

            $redirectQuery = $wantRedirect ? '?redirect='. rawurlencode($redirectUrl) : '';
            $route_url = "/user/login";


            $redirect = $this->getMock('Zend\Mvc\Controller\Plugin\Redirect', array('toUrl'));
            $redirect->expects($this->any())
                     ->method('toUrl')
                     ->with($route_url . $redirectQuery)
                     ->will($this->returnCallback(function ($url) use (&$response) {
                         $response->getHeaders()->addHeaderLine('Location', $url);
                         $response->setStatusCode(302);

                         return $response;
                     }));

            $this->pluginManagerPlugins['redirect']= $redirect;


            $response = new Response();
            $url = $this->getMock('Zend\Mvc\Controller\Plugin\Url', array('fromRoute'));
            $url->expects($this->once())
                     ->method('fromRoute')
                     ->with($controller::ROUTE_LOGIN)
                     ->will($this->returnValue($route_url));

            $this->pluginManagerPlugins['url']= $url;
            $TEST = true;
        }


        $controller->setLoginForm($form);
        $result = $controller->loginAction();

        if ($isValid) {
            $this->assertSame($expectedResult, $result);
        } else {
            $this->assertInstanceOf('\Zend\Http\Response', $result);
            $this->assertEquals($response, $result);
            $this->assertEquals($route_url . $redirectQuery, $result->getHeaders()->get('Location')->getFieldValue());
        }
    }

    /**
     * @dataProvider providerTrueOrFalse
     */
    public function testLoginActionIsNotPost($redirect)
    {
        $plugin = $this->setUpZfcUserAuthenticationPlugin(array(
            'hasIdentity'=>false
        ));

        $flashMessenger = $this->getMock('Zend\Mvc\Controller\Plugin\FlashMessenger');
        $flashMessenger->expects($this->once())
                       ->method('setNamespace')
                       ->with('zfcuser-login-form')
                       ->will($this->returnSelf());

        $this->pluginManagerPlugins['flashMessenger']= $flashMessenger;

        $request = $this->getMock('Zend\Http\Request');
        $request->expects($this->once())
                ->method('isPost')
                ->will($this->returnValue(false));

        $form = $this->getMockBuilder('ZfcUser\Form\Login')
                     ->disableOriginalConstructor()
                     ->getMock();
        $form->expects($this->never())
             ->method('isValid');



        $this->options->expects($this->any())
                      ->method('getUseRedirectParameterIfPresent')
                      ->will($this->returnValue((bool) $redirect));
        if ($redirect) {
            $params = new Parameters();
            $params->set('redirect', 'http://localhost/');

            $request->expects($this->any())
                    ->method('getQuery')
                    ->will($this->returnValue($params));
        }

        $this->helperMakePropertyAccessable($this->controller, 'request', $request);

        $this->controller->setLoginForm($form);
        $result = $this->controller->loginAction();

        $this->assertArrayHasKey('loginForm', $result);
        $this->assertArrayHasKey('redirect', $result);
        $this->assertArrayHasKey('enableRegistration', $result);

        $this->assertInstanceOf('\ZfcUser\Form\Login', $result['loginForm']);
        $this->assertSame($form, $result['loginForm']);

        if ($redirect) {
            $this->assertEquals('http://localhost/', $result['redirect']);
        } else {
            $this->assertFalse($result['redirect']);
        }

        $this->assertEquals($this->options->getEnableRegistration(), $result['enableRegistration']);
    }


    /**
     * @dataProvider providerTestLogout
     */
    public function testLogoutAction($withRedirect, $post, $query)
    {
        $controller = $this->controller;

        $adapter = $this->getMock('ZfcUser\Authentication\Adapter\AdapterChain');
        $adapter->expects($this->once())
                ->method('resetAdapters');

        $adapter->expects($this->once())
                ->method('logoutAdapters');

        $service = $this->getMock('Zend\Authentication\AuthenticationService');
        $service->expects($this->once())
                ->method('clearIdentity');

        $this->setUpZfcUserAuthenticationPlugin(array(
            'getAuthAdapter'=>$adapter,
            'getAuthService'=>$service
        ));


        $params = $this->getMock('\Zend\Mvc\Controller\Plugin\Params');
        $params->expects($this->any())
               ->method('__invoke')
               ->will($this->returnSelf());
        $params->expects($this->once())
               ->method('fromPost')
               ->will($this->returnCallback(function ($key, $default) use ($post) {
                   return $post ?: $default;
               }));
        $params->expects($this->once())
               ->method('fromQuery')
               ->will($this->returnCallback(function ($key, $default) use ($query) {
                   return $query ?: $default;
               }));
        $this->pluginManagerPlugins['params'] = $params;

        $response = new Response();

        $redirect = $this->getMock('Zend\Mvc\Controller\Plugin\Redirect');
        $redirect->expects($this->any())
                 ->method('toRoute')
                 ->will($this->returnValue($response));

        if ($withRedirect) {
            $expectedLocation = $post ?: $query ?: false;
            $this->options->expects($this->once())
                          ->method('getUseRedirectParameterIfPresent')
                          ->will($this->returnValue((bool) $withRedirect));
            $redirect->expects($this->any())
                     ->method('toUrl')
                     ->with($expectedLocation)
                     ->will($this->returnValue($response));
        } else {
            $expectedLocation = "/user/logout";
            $this->options->expects($this->once())
                          ->method('getLogoutRedirectRoute')
                          ->will($this->returnValue($expectedLocation));
            $redirect->expects($this->any())
                     ->method('toRoute')
                     ->with($expectedLocation)
                     ->will($this->returnValue($response));
        }

        $this->pluginManagerPlugins['redirect']= $redirect;

        $result = $controller->logoutAction();

        $this->assertInstanceOf('\Zend\Http\Response', $result);
        $this->assertSame($response, $result);
    }

    public function testAuthenticateAction()
    {
        $this->setUpZfcUserAuthenticationPlugin(array(
            'hasIdentity'=>true
        ));
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

    /**
     * @dataProvider providerTestSetterGetterServices
     */
    public function testSetterGetterServices(
        $methode,
        $useServiceLocator,
        $servicePrototype,
        $serviceName,
        $callback = null
    ) {
        $controller = new Controller;

        $controller->setPluginManager($this->pluginManager);


//         $controller = $this->controller;

        if (is_callable($callback)) {
            call_user_func($callback, $this, $controller);
        }


        if ($useServiceLocator) {
            $serviceLocator = $this->getMock('\Zend\ServiceManager\ServiceLocatorInterface');
            $serviceLocator->expects($this->once())
                           ->method('get')
                           ->with($serviceName)
                           ->will($this->returnValue($servicePrototype));

            $controller->setServiceLocator($serviceLocator);
        } else {
            call_user_func(array($controller, 'set' . $methode), $servicePrototype);
        }

        $result = call_user_func(array($controller, 'get' . $methode));
        $this->assertInstanceOf(get_class($servicePrototype), $result);
        $this->assertSame($servicePrototype, $result);

        // we need two check for every case
        $result = call_user_func(array($controller, 'get' . $methode));
        $this->assertInstanceOf(get_class($servicePrototype), $result);
        $this->assertSame($servicePrototype, $result);
    }

    public function providerTrueOrFalse ()
    {
        return array(
            array(true),
            array(false),
        );
    }

    public function providerTrueOrFalseX2 ()
    {
        return array(
            array(true,true),
            array(true,false),
            array(false,true),
            array(false,false),
        );
    }

    public function providerTestLogout ()
    {
        return array(
            array(false, false, false),
            array(true, false, false),
            array(true, 'localhost/test1', false),
            array(true, 'localhost/test1', 'localhost/test2'),
            array(true, false,              'localhost/test2'),
        );
    }

    public function providerTestSetterGetterServices ()
    {
        $that = $this;
        $loginFormCallback[] = function ($that, $controller) {
            $flashMessenger = $that->getMock(
                'Zend\Mvc\Controller\Plugin\FlashMessenger'
            );
            $that->pluginManagerPlugins['flashMessenger']= $flashMessenger;

            $flashMessenger->expects($that->any())
                           ->method('setNamespace')
                           ->with('zfcuser-login-form')
                           ->will($that->returnSelf());

            $flashMessenger->expects($that->once())
                           ->method('getMessages')
                           ->will($that->returnValue(array()));
        };
        $loginFormCallback[] = function ($that, $controller) {
            $flashMessenger = $that->getMock(
                'Zend\Mvc\Controller\Plugin\FlashMessenger'
            );
            $that->pluginManagerPlugins['flashMessenger']= $flashMessenger;

            $flashMessenger->expects($that->any())
                           ->method('setNamespace')
                           ->with('zfcuser-login-form')
                           ->will($that->returnSelf());

            $flashMessenger->expects($that->once())
                           ->method('getMessages')
                           ->will($that->returnValue(array("message1","message2")));
        };



        return array(
            // $methode, $useServiceLocator, $servicePrototype, $serviceName, $loginFormCallback
            array('UserService', true, new UserService(), 'zfcuser_user_service' ),
            array('UserService', false, new UserService(), null ),
            array('RegisterForm', true, new Form(), 'zfcuser_register_form' ),
            array('RegisterForm', false, new Form(), null ),
            array('ChangePasswordForm', true, new Form(), 'zfcuser_change_password_form' ),
            array('ChangePasswordForm', false, new Form(), null ),
            array('ChangeEmailForm', true, new Form(), 'zfcuser_change_email_form' ),
            array('ChangeEmailForm', false, new Form(), null ),
            array('LoginForm', true, new Form(), 'zfcuser_login_form', $loginFormCallback[0] ),
            array('LoginForm', true, new Form(), 'zfcuser_login_form', $loginFormCallback[1] ),
            array('LoginForm', false, new Form(), null, $loginFormCallback[0] ),
            array('LoginForm', false, new Form(), null, $loginFormCallback[1] ),
            array('Options', true, new ModuleOptions(), 'zfcuser_module_options' ),
            array('Options', false, new ModuleOptions(), null ),
        );
    }

    /**
     *
     * @param mixed $objectOrClass
     * @param string $property
     * @param mixed $value = null
     * @return \ReflectionProperty
     */
    public function helperMakePropertyAccessable ($objectOrClass, $property, $value = null)
    {
        $reflectionProperty = new \ReflectionProperty($objectOrClass, $property);
        $reflectionProperty->setAccessible(true);

        if ($value !== null) {
            $reflectionProperty->setValue($objectOrClass, $value);
        }
        return $reflectionProperty;
    }

    public function helperMockCallbackPluginManagerGet($key)
    {
        if ($key=="flashMessenger" && !array_key_exists($key, $this->pluginManagerPlugins)) {
            echo "\n\n";
            var_dump(array_key_exists($key, $this->pluginManagerPlugins), array_keys($this->pluginManagerPlugins));
            exit;
        }
        return (array_key_exists($key, $this->pluginManagerPlugins))
            ? $this->pluginManagerPlugins[$key]
            : null;
    }
}
