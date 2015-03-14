<?php

namespace ZfcUserTest\Form;

use ZfcUser\Form\Base as Form;

class BaseTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $form = new Form();

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
