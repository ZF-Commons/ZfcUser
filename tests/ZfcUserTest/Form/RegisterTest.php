<?php

namespace ZfcUserTest\Form;

use ZfcUser\Form\Register as Form;

class RegisterTest extends \PHPUnit_Framework_TestCase
{
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

    public function testSetCaptchaElement()
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

        $captcha = $this->getMock('\Zend\Form\Element\Captcha');
        $form = new Form(null, $options);

        $form->setCaptchaElement($captcha);

        $reflection = $this->helperMakePropertyAccessable($form, 'captchaElement');
        $this->assertSame($captcha, $reflection->getValue($form));
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
}
