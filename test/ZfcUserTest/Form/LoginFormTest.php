<?php

namespace ZfcUserTest\Form;

use ZfcUser\Form\LoginForm;

class LoginFormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ZfcUser\Form\LoginForm::__construct
     */
    public function testFormConstruction()
    {
        $form = new LoginForm();
        $this->assertCount(3, $form->getElements());
    }

    /**
     * @covers ZfcUser\Form\LoginForm::getInputFilterSpecification
     */
    public function testFormFilter()
    {
        $form   = new LoginForm();
        $filter = $form->getInputFilterSpecification();
        $this->assertCount(2, $filter);
    }
}