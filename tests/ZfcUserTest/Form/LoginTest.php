<?php

namespace ZfcUserTest\Form;

use ZfcUser\Form\Login as Form;

class LoginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ZfcUser\Form\ChangePassword::__construct
     */
    public function testConstruct()
    {
        $options = $this->getMock('ZfcUser\Options\AuthenticationOptionsInterface');
        $options->expects($this->once())
                ->method('getAuthIdentityFields')
                ->will($this->returnValue(array()));

        $form = new Form(null, $options);

        $elements = $form->getElements();

        $this->assertArrayHasKey('identity', $elements);
        $this->assertArrayHasKey('credential', $elements);
    }

    /**
     * @covers ZfcUser\Form\ChangePassword::getAuthenticationOptions
     * @covers ZfcUser\Form\ChangePassword::setAuthenticationOptions
     */
    public function testSetGetAuthenticationOptions()
    {
        $options = $this->getMock('ZfcUser\Options\AuthenticationOptionsInterface');
        $options->expects($this->once())
                ->method('getAuthIdentityFields')
                ->will($this->returnValue(array()));
        $form = new Form(null, $options);

        $this->assertSame($options, $form->getAuthenticationOptions());
    }
}
