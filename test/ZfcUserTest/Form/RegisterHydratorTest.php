<?php

namespace ZfcUserTest\Form;

use ArrayObject;
use ZfcUser\Form\PasswordStrategy;
use ZfcUser\Form\RegisterHydrator;
use ZfcUser\ModuleOptions;
use ZfcUserTest\Asset\Entity;

class RegisterHydratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RegisterHydrator
     */
    protected $hydrator;

    /**
     * @var PasswordStrategy
     */
    protected $strategy;

    public function setUp()
    {
        $this->strategy = new PasswordStrategy(new ModuleOptions());
        $this->hydrator = new RegisterHydrator($this->strategy);
    }

    /**
     * @covers \ZfcUser\Form\RegisterHydrator::__construct
     * @covers \ZfcUser\Form\RegisterHydrator::extract
     */
    public function testExtract()
    {
        $entity = new Entity();
        $this->hydrator->hydrate(array('foo' => 'bar', 'password' => 'baz'), $entity);

        $this->assertEquals('bar', $entity->getFoo());
        $this->assertEquals('$2y$14$Y2hhbmdlX3RoZV9kZWZhdOpaaa2x51qGanveY9TapJ.CFsqSNG.7S', $entity->getPassword());
    }
}