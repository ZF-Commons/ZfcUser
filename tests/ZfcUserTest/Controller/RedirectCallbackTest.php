<?php

namespace ZfcUserTest\Controller;

use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteInterface;
use Zend\Mvc\Router\RouteMatch;
use ZfcUser\Controller\RedirectCallback;
use ZfcUser\Options\ModuleOptions;

class RedirectCallbackTest extends \PHPUnit_Framework_TestCase
{

    /** @var RedirectCallback */
    protected $redirectCallback;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ModuleOptions */
    protected $moduleOptions;

    /** @var  \PHPUnit_Framework_MockObject_MockObject|RouteInterface */
    protected $router;

    /** @var  \PHPUnit_Framework_MockObject_MockObject|Application */
    protected $application;

    /** @var  \PHPUnit_Framework_MockObject_MockObject|Request */
    protected $request;

    /** @var  \PHPUnit_Framework_MockObject_MockObject|Response */
    protected $response;

    /** @var  \PHPUnit_Framework_MockObject_MockObject|MvcEvent */
    protected $mvcEvent;

    /** @var  \PHPUnit_Framework_MockObject_MockObject|RouteMatch */
    protected $routeMatch;

    public function setUp()
    {
        $this->router = $this->getMockBuilder('Zend\Mvc\Router\RouteInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->moduleOptions = $this->getMockBuilder('ZfcUser\Options\ModuleOptions')
            ->disableOriginalConstructor()
            ->getMock();

        $this->application = $this->getMockBuilder('Zend\Mvc\Application')
            ->disableOriginalConstructor()
            ->getMock();
        $this->setUpApplication();

        $this->redirectCallback = new RedirectCallback(
            $this->application,
            $this->router,
            $this->moduleOptions
        );
    }

    public function testInvoke()
    {
        $url = 'someUrl';

        $this->routeMatch->expects($this->once())
            ->method('getMatchedRouteName')
            ->will($this->returnValue('someRoute'));

        $headers = $this->getMock('Zend\Http\Headers');
        $headers->expects($this->once())
            ->method('addHeaderLine')
            ->with('Location', $url);

        $this->router->expects($this->any())
            ->method('assemble')
            ->with(array(), array('name' => 'zfcuser'))
            ->will($this->returnValue($url));

        $this->response->expects($this->once())
            ->method('getHeaders')
            ->will($this->returnValue($headers));

        $this->response->expects($this->once())
            ->method('setStatusCode')
            ->with(302);

        $result = $this->redirectCallback->__invoke();

        $this->assertSame($this->response, $result);
    }

    /**
     * @dataProvider providerGetRedirectRouteFromRequest
     */
    public function testGetRedirectRouteFromRequest($get, $post, $getRouteExists, $postRouteExists)
    {
        $expectedResult = false;

        $this->request->expects($this->once())
            ->method('getQuery')
            ->will($this->returnValue($get));

        if ($get) {
            $this->router->expects($this->any())
                ->method('assemble')
                ->with(array(), array('name' => $get))
                ->will($getRouteExists);

            if ($getRouteExists == $this->returnValue(true)) {
                $expectedResult = $get;
            }
        }

        if (!$get || !$getRouteExists) {
            $this->request->expects($this->once())
                ->method('getPost')
                ->will($this->returnValue($post));

            if ($post) {
                $this->router->expects($this->any())
                    ->method('assemble')
                    ->with(array(), array('name' => $post))
                    ->will($postRouteExists);

                if ($postRouteExists == $this->returnValue(true)) {
                    $expectedResult = $post;
                }
            }
        }

        $method = new \ReflectionMethod(
            'ZfcUser\Controller\RedirectCallback',
            'getRedirectRouteFromRequest'
        );
        $method->setAccessible(true);
        $result = $method->invoke($this->redirectCallback);

        $this->assertSame($expectedResult, $result);
    }

    public function providerGetRedirectRouteFromRequest()
    {
        return array(
            array('user', false, $this->returnValue('route'), false),
            array('user', false, $this->returnValue('route'), $this->returnValue(true)),
            array('user', 'user', $this->returnValue('route'), $this->returnValue(true)),
            array('user', 'user', $this->throwException(new \Zend\Mvc\Router\Exception\RuntimeException), $this->returnValue(true)),
            array('user', 'user', $this->throwException(new \Zend\Mvc\Router\Exception\RuntimeException), $this->throwException(new \Zend\Mvc\Router\Exception\RuntimeException)),
            array(false, 'user', false, $this->returnValue(true)),
            array(false, 'user', false, $this->throwException(new \Zend\Mvc\Router\Exception\RuntimeException)),
            array(false, 'user', false, $this->throwException(new \Zend\Mvc\Router\Exception\RuntimeException)),
        );
    }

    public function testRouteExistsRouteExists()
    {
        $route = 'existingRoute';

        $this->router->expects($this->once())
            ->method('assemble')
            ->with(array(), array('name' => $route));

        $method = new \ReflectionMethod(
            'ZfcUser\Controller\RedirectCallback',
            'routeExists'
        );
        $method->setAccessible(true);
        $result = $method->invoke($this->redirectCallback, $route);

        $this->assertTrue($result);
    }

    public function testRouteExistsRouteDoesntExists()
    {
        $route = 'existingRoute';

        $this->router->expects($this->once())
            ->method('assemble')
            ->with(array(), array('name' => $route))
            ->will($this->throwException(new \Zend\Mvc\Router\Exception\RuntimeException));

        $method = new \ReflectionMethod(
            'ZfcUser\Controller\RedirectCallback',
            'routeExists'
        );
        $method->setAccessible(true);
        $result = $method->invoke($this->redirectCallback, $route);

        $this->assertFalse($result);
    }

    /**
     * @dataProvider providerGetRedirectNoRedirectParam
     */
    public function testGetRedirectNoRedirectParam($currentRoute, $optionsReturn, $expectedResult, $optionsMethod)
    {
        $this->moduleOptions->expects($this->once())
            ->method('getUseRedirectParameterIfPresent')
            ->will($this->returnValue(true));

        $this->router->expects($this->at(0))
            ->method('assemble');
        $this->router->expects($this->at(1))
            ->method('assemble')
            ->with(array(), array('name' => $optionsReturn))
            ->will($this->returnValue($expectedResult));

        if ($optionsMethod) {
            $this->moduleOptions->expects($this->never())
                ->method($optionsMethod)
                ->will($this->returnValue($optionsReturn));
        }
        $method = new \ReflectionMethod(
            'ZfcUser\Controller\RedirectCallback',
            'getRedirect'
        );
        $method->setAccessible(true);
        $result = $method->invoke($this->redirectCallback, $currentRoute, $optionsReturn);

        $this->assertSame($expectedResult, $result);
    }

    public function providerGetRedirectNoRedirectParam()
    {
        return array(
            array('zfcuser/login', 'zfcuser', '/user', 'getLoginRedirectRoute'),
            array('zfcuser/logout', 'zfcuser/login', '/user/login', 'getLogoutRedirectRoute'),
            array('testDefault', 'zfcuser', '/home', false),
        );
    }

    public function testGetRedirectWithOptionOnButNoRedirect()
    {
        $route = 'zfcuser/login';
        $redirect = false;
        $expectedResult = '/user/login';

        $this->moduleOptions->expects($this->once())
            ->method('getUseRedirectParameterIfPresent')
            ->will($this->returnValue(true));

        $this->moduleOptions->expects($this->once())
            ->method('getLoginRedirectRoute')
            ->will($this->returnValue($route));

        $this->router->expects($this->once())
            ->method('assemble')
            ->with(array(), array('name' => $route))
            ->will($this->returnValue($expectedResult));

        $method = new \ReflectionMethod(
            'ZfcUser\Controller\RedirectCallback',
            'getRedirect'
        );
        $method->setAccessible(true);
        $result = $method->invoke($this->redirectCallback, $route, $redirect);

        $this->assertSame($expectedResult, $result);
    }

    public function testGetRedirectWithOptionOnRedirectDoesntExists()
    {
        $route = 'zfcuser/login';
        $redirect = 'doesntExists';
        $expectedResult = '/user/login';

        $this->moduleOptions->expects($this->once())
            ->method('getUseRedirectParameterIfPresent')
            ->will($this->returnValue(true));

        $this->router->expects($this->at(0))
            ->method('assemble')
            ->with(array(), array('name' => $redirect))
            ->will($this->throwException(new \Zend\Mvc\Router\Exception\RuntimeException));

        $this->router->expects($this->at(1))
            ->method('assemble')
            ->with(array(), array('name' => $route))
            ->will($this->returnValue($expectedResult));

        $this->moduleOptions->expects($this->once())
            ->method('getLoginRedirectRoute')
            ->will($this->returnValue($route));

        $method = new \ReflectionMethod(
            'ZfcUser\Controller\RedirectCallback',
            'getRedirect'
        );
        $method->setAccessible(true);
        $result = $method->invoke($this->redirectCallback, $route, $redirect);

        $this->assertSame($expectedResult, $result);
    }

    private function setUpApplication()
    {
        $this->request = $this->getMockBuilder('Zend\Http\PhpEnvironment\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->response = $this->getMockBuilder('Zend\Http\PhpEnvironment\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $this->routeMatch = $this->getMockBuilder('Zend\Mvc\Router\RouteMatch')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mvcEvent = $this->getMockBuilder('Zend\Mvc\MvcEvent')
            ->disableOriginalConstructor()
            ->getMock();
        $this->mvcEvent->expects($this->any())
            ->method('getRouteMatch')
            ->will($this->returnValue($this->routeMatch));


        $this->application->expects($this->any())
            ->method('getMvcEvent')
            ->will($this->returnValue($this->mvcEvent));
        $this->application->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($this->request));
        $this->application->expects($this->any())
            ->method('getResponse')
            ->will($this->returnValue($this->response));
    }
}
