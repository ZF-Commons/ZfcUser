<?php

namespace ZfcUserTest\Form;

use Zend\Crypt\Password\Bcrypt;
use ZfcUser\Form\PasswordStrategy;
use ZfcUser\Options\ModuleOptions;

class PasswordStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \ZfcUser\Form\PasswordStrategy::extract
     */
    public function testExtract()
    {
        $options  = new ModuleOptions();
        $strategy = new PasswordStrategy($options);

        $this->assertEquals('unchanged', $strategy->extract('unchanged'));
    }

    /**
     * @covers \ZfcUser\Form\PasswordStrategy::__construct
     * @covers \ZfcUser\Form\PasswordStrategy::hydrate
     */
    public function testHydrate()
    {
        $options  = new ModuleOptions();
        $options->setPasswordCost(4);
        $options->setPasswordSalt('zfcuser_strategy_test');

        $strategy = new PasswordStrategy($options);

        $crypt = new Bcrypt();
        $crypt->setCost($options->getPasswordCost());
        $crypt->setSalt($options->getPasswordSalt());
        $foo = $crypt->create('foo');

        $this->assertEquals($foo, $strategy->hydrate('foo'));
    }
}