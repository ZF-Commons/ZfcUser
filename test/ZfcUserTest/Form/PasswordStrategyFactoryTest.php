<?php

namespace ZfcUserTest\Form;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Form\PasswordStrategyFactory;
use ZfcUser\ModuleOptions;

class PasswordStrategyFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \ZfcUser\Form\PasswordStrategyFactory::createService
     */
    public function testInstanceReturned()
    {
        $sm = new ServiceManager();
        $sm->setService('ZfcUser\ModuleOptions', new ModuleOptions());

        $factory  = new PasswordStrategyFactory();
        $strategy = $factory->createService($sm);
        $this->assertInstanceOf('ZfcUser\Form\PasswordStrategy', $strategy);
    }
}