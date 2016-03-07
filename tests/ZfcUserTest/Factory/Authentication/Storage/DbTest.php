<?php
namespace ZfcUserTest\Authentication\Storage\Factory;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Factory\Authentication\Storage\Db;

class DbTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager;

        $mapper = $this->getMockForAbstractClass(
            'ZfcUser\Mapper\UserInterface'
        );
        $serviceManager->setService('zfcuser_user_mapper', $mapper);

        $factory = new Db();

        $this->assertInstanceOf('ZfcUser\Authentication\Storage\Db', $factory->createService($serviceManager));
    }
}
