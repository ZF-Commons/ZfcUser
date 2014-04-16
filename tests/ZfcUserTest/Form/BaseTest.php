<?php

namespace ZfcUserTest\Form;

use ZfcUserTest\Form\TestAsset\BaseExtension as Form;

class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestConstruct
     */
    public function testConstruct($useCaptcha = true, $captchaOptions = array())
    {
        $options = $this->getMock('ZfcUser\Options\RegistrationOptionsInterface');
        $options->expects($this->once())
                ->method('getUseRegistrationFormCaptcha')
                ->will($this->returnValue($useCaptcha));

        if ($useCaptcha) {
            $options->expects($this->once())
                    ->method('getFormCaptchaOptions')
                    ->will($this->returnValue($captchaOptions));
        }

        $form = new Form($options);

        $elements = $form->getElements();

        $this->assertArrayHasKey('username', $elements);
        $this->assertArrayHasKey('email', $elements);
        $this->assertArrayHasKey('display_name', $elements);
        $this->assertArrayHasKey('password', $elements);
        $this->assertArrayHasKey('passwordVerify', $elements);
        $this->assertArrayHasKey('submit', $elements);
        $this->assertArrayHasKey('userId', $elements);
    }

    public function providerTestConstruct()
    {
        return array(
            array(true, array('class'=>'dumb')),
            array(false, array('class'=>'dumb'))
        );
    }
}
