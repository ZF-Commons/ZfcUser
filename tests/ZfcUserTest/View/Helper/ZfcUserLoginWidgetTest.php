<?php

namespace ZfcUserTest\View\Helper;

use ZfcUser\View\Helper\ZfcUserLoginWidget as ViewHelper;

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

    /**
     * @covers ZfcUser\View\Helper\ZfcUserLoginWidget::__invoke
     */
    public function testInvokeWithRender()
    {
        $this->view->expects($this->once())
            ->method('render');

        $this->helper->__invoke(array(
            'render' => true,
            'redirect' => 'zfcUser'
        ));
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
