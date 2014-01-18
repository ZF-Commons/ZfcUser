<?php

namespace ZfcUserTest\Form;

use ZfcUserTest\Form\BaseExtension as Form;

class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ZfcUser\Form\Base::__construct
     */
    public function testConstruct()
    {
        $options = $this->getMock('ZfcUser\Options\RegistrationOptionsInterface');
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
}
