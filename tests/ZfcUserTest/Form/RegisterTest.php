<?php

namespace ZfcUserTest\Form;

use ZfcUser\Form\Register as Form;

class RegisterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ZfcUser\Form\Register::__construct
     */
    public function testConstruct()
    {
        $options = $this->getMock('ZfcUser\Options\RegistrationOptionsInterface');
        $options->expects($this->once())
            ->method('getEnableUsername')
            ->will($this->returnValue(false));
        $options->expects($this->once())
            ->method('getEnableDisplayName')
            ->will($this->returnValue(false));
        $options->expects($this->any())
            ->method('getUseRegistrationFormCaptcha')
            ->will($this->returnValue(false));
        $form = new Form(null, $options);

        $elements = $form->getElements();

        $this->assertArrayNotHasKey('userId', $elements);
        $this->assertArrayNotHasKey('username', $elements);
        $this->assertArrayNotHasKey('display_name', $elements);
        $this->assertArrayHasKey('email', $elements);
        $this->assertArrayHasKey('password', $elements);
        $this->assertArrayHasKey('passwordVerify', $elements);
    }

    /**
     * @covers ZfcUser\Form\Register::setRegistrationOptions
     * @covers ZfcUser\Form\Register::getRegistrationOptions
     */
    public function testSetGetRegistrationOptions()
    {
        $options = $this->getMock('ZfcUser\Options\RegistrationOptionsInterface');
        $options->expects($this->once())
                ->method('getEnableUsername')
                ->will($this->returnValue(false));
        $options->expects($this->once())
                ->method('getEnableDisplayName')
                ->will($this->returnValue(false));
        $options->expects($this->any())
                ->method('getUseRegistrationFormCaptcha')
                ->will($this->returnValue(false));
        $form = new Form(null, $options);

        $this->assertSame($options, $form->getRegistrationOptions());

        $optionsNew = $this->getMock('ZfcUser\Options\RegistrationOptionsInterface');
        $form->setRegistrationOptions($optionsNew);
        $this->assertSame($optionsNew, $form->getRegistrationOptions());
    }

    /**
     * @covers ZfcUser\Form\Register::getRegistrationOptions
     */
    public function testSetCaptchaElement()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
