<?php

namespace ZfcUserTest\View\Helper;

use ZfcUser\View\Helper\ZfcUserLoginWidget as ViewHelper;

class ZfcUserLoginWidget extends \PHPUnit_Framework_TestCase
{
    protected $helper;

    public function setUp()
    {
        $this->helper = new \ZfcUser\View\Helper\ZfcUserLoginWidget;
    }

    public function testInvokeWithRender()
    {
        $this->helper->__invoke(array());
    }

    public function testInvokeWithoutRender()
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

    public function testSetViewTemplate()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
