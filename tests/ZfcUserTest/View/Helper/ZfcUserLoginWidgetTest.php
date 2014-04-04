<?php

namespace ZfcUserTest\View\Helper;

use ZfcUser\View\Helper\ZfcUserLoginWidget as ViewHelper;
use Zend\View\Model\ViewModel;

class ZfcUserLoginWidgetTest extends \PHPUnit_Framework_TestCase
{
    protected $helper;

    protected $view;

    public function setUp()
    {
        $this->helper = new ViewHelper;

        $view = $this->getMock('Zend\View\Renderer\RendererInterface');
        $this->view = $view;

        $this->helper->setView($view);
    }

    public function providerTestInvokeWithRender()
    {
        $attr = array();
        $attr[] = array(
            array(
                'render' => true,
                'redirect' => 'zfcUser'
            ),
            array(
                'loginForm' => null,
                'redirect' => 'zfcUser'
            ),
        );
        $attr[] = array(
            array(
                'redirect' => 'zfcUser'
            ),
            array(
                'loginForm' => null,
                'redirect' => 'zfcUser'
            ),
        );
        $attr[] = array(
            array(
                'render' => true,
            ),
            array(
                'loginForm' => null,
                'redirect' => false
            ),
        );

        return $attr;
    }

    /**
     * @covers ZfcUser\View\Helper\ZfcUserLoginWidget::__invoke
     * @dataProvider providerTestInvokeWithRender
     */
    public function testInvokeWithRender($option, $expect)
    {
        /**
         * @var $viewModel \Zend\View\Model\ViewModels
         */
        $viewModel = null;

        $this->view->expects($this->at(0))
             ->method('render')
             ->will($this->returnCallback(function ($vm) use (&$viewModel) {
                 $viewModel = $vm;
                 return "test";
             }));

        $result = $this->helper->__invoke($option);

        $this->assertNotInstanceOf('Zend\View\Model\ViewModel', $result);
        $this->assertInternalType('string', $result);


        $this->assertInstanceOf('Zend\View\Model\ViewModel', $viewModel);
        foreach ($expect as $name => $value) {
            $this->assertEquals($value, $viewModel->getVariable($name, "testDefault"));
        }
    }

    /**
     * @covers ZfcUser\View\Helper\ZfcUserLoginWidget::__invoke
     */
    public function testInvokeWithoutRender()
    {
        $result = $this->helper->__invoke(array(
            'render' => false,
            'redirect' => 'zfcUser'
        ));

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $this->assertEquals('zfcUser', $result->redirect);
    }

    /**
     * @covers ZfcUser\View\Helper\ZfcUserLoginWidget::setLoginForm
     * @covers ZfcUser\View\Helper\ZfcUserLoginWidget::getLoginForm
     */
    public function testSetGetLoginForm()
    {
        $loginForm = $this->getMockBuilder('ZfcUser\Form\Login')->disableOriginalConstructor()->getMock();

        $this->helper->setLoginForm($loginForm);
        $this->assertInstanceOf('ZfcUser\Form\Login', $this->helper->getLoginForm());
    }

    /**
     * @covers ZfcUser\View\Helper\ZfcUserLoginWidget::setViewTemplate
     */
    public function testSetViewTemplate()
    {
        $this->helper->setViewTemplate('zfcUser');

        $reflectionClass = new \ReflectionClass('ZfcUser\View\Helper\ZfcUserLoginWidget');
        $reflectionProperty = $reflectionClass->getProperty('viewTemplate');
        $reflectionProperty->setAccessible(true);

        $this->assertEquals('zfcUser', $reflectionProperty->getValue($this->helper));
    }
}
