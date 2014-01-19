<?php

namespace ZfcUserTest\Form;

use ZfcUser\Form\ChangePassword as Form;

class ChangePasswordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ZfcUser\Form\ChangePassword::__construct
     */
    public function testConstruct()
    {
        $options = $this->getMock('ZfcUser\Options\AuthenticationOptionsInterface');

        $form = new Form(null, $options);

        $elements = $form->getElements();

        $this->assertArrayHasKey('identity', $elements);
        $this->assertArrayHasKey('credential', $elements);
        $this->assertArrayHasKey('newCredential', $elements);
        $this->assertArrayHasKey('newCredentialVerify', $elements);
    }

    /**
     * @covers ZfcUser\Form\ChangePassword::getAuthenticationOptions
     * @covers ZfcUser\Form\ChangePassword::setAuthenticationOptions
     */
    public function testSetGetAuthenticationOptions()
    {
        $options = $this->getMock('ZfcUser\Options\AuthenticationOptionsInterface');
        $form = new Form(null, $options);

        $this->assertSame($options, $form->getAuthenticationOptions());
    }
}
