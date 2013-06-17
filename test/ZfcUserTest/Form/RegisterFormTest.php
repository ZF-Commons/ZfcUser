<?php

namespace ZfcUserTest\Form;

use ZfcUser\Form\RegisterForm;

class RegisterFormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ZfcUser\Form\RegisterForm::__construct
     */
    public function testFormConstruction()
    {
        $form = new RegisterForm();
        $this->assertCount(5, $form->getElements());
    }

    /**
     * @covers ZfcUser\Form\RegisterForm::getInputFilterSpecification
     */
    public function testFormFilter()
    {
        $form   = new RegisterForm();
        $filter = $form->getInputFilterSpecification();
        $this->assertCount(3, $filter);
    }
}