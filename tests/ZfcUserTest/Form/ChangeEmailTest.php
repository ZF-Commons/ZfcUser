<?php

namespace ZfcUserTest\Form;

use ZfcUser\Form\ChangeEmail as Form;

class ChangeEmailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ZfcUser\Form\ChangeEmail::__construct
     */
    public function testConstruct()
    {
        $options = $this->getMock('ZfcUser\Options\AuthenticationOptionsInterface');

        $form = new Form(null, $options);

        $elements = $form->getElements();

        $this->assertArrayHasKey('identity', $elements);
        $this->assertArrayHasKey('newIdentity', $elements);
        $this->assertArrayHasKey('newIdentityVerify', $elements);
        $this->assertArrayHasKey('credential', $elements);
    }

    /**
     * @covers ZfcUser\Form\ChangeEmail::getAuthenticationOptions
     * @covers ZfcUser\Form\ChangeEmail::setAuthenticationOptions
     */
    public function testSetGetAuthenticationOptions()
    {
        $options = $this->getMock('ZfcUser\Options\AuthenticationOptionsInterface');
        $form = new Form(null, $options);

        $this->assertSame($options, $form->getAuthenticationOptions());
    }
}
