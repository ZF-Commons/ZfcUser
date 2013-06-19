<?php

namespace ZfcUser\Service;

use Zend\ServiceManager\ServiceManager;
use ZfcUserTest\Asset\Entity;
use ZfcUserTest\Asset\ServiceFactory;

class AbstractServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \ZfcUser\Service\AbstractServiceFactory::get
     */
    public function testGetHandlesStrings()
    {
        $sm      = new ServiceManager();
        $factory = new ServiceFactory('ZfcUserTest\Asset\Entity');
        $user    = $factory->createService($sm);

        $this->assertInstanceOf('ZfcUserTest\Asset\Entity', $user);
    }

    /**
     * @covers \ZfcUser\Service\AbstractServiceFactory::get
     */
    public function testGetHandlesServices()
    {
        $sm = new ServiceManager();
        $sm->setService('test', new Entity());

        $factory = new ServiceFactory('test');
        $user    = $factory->createService($sm);

        $this->assertInstanceOf('ZfcUserTest\Asset\Entity', $user);
    }
}
