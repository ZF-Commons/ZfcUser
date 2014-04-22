<?php
namespace ZfcUserTest\Factory;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Factory\ModuleOptionsFactory;
use ZfcUser\Options\ModuleOptions;

class ModuleOptionsFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestFactory
     */
    public function testFactory($config)
    {
        $serviceManager = new ServiceManager;
        $serviceManager->setService('Config', $config);

        $factory = new ModuleOptionsFactory;
        $defaultOption = new ModuleOptions(array());

        $object = $factory->createService($serviceManager);

        $this->assertInstanceOf('ZfcUser\Options\ModuleOptions', $object);

        if (isset($config['zfcuser'])) {
            $this->assertNotEquals($defaultOption->getLoginRedirectRoute(), $object->getLoginRedirectRoute());
            $this->assertEquals($config['zfcuser']['loginRedirectRoute'], $object->getLoginRedirectRoute());
        } else {
            $this->assertEquals($defaultOption->getLoginRedirectRoute(), $object->getLoginRedirectRoute());
        }
    }

    public function providerTestFactory()
    {
        return array(
            array(array()), // config without zfcuser
            array(array('zfcuser'=>array(
                'loginRedirectRoute'=>'user',
            )))
        );
    }
}
