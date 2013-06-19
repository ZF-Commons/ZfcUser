<?php

namespace ZfcUserTest\Service;

use Zend\EventManager\EventManager;
use ZfcUserTest\Asset\LoginListener;
use ZfcUserTest\Asset\PluginService;
use ZfcUserTest\Asset\RegisterListener;

class AbstractPluginServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \ZfcUser\Service\AbstractPluginService::setEventManager
     */
    public function testSetEventManager()
    {
        $service = new PluginService();
        $em      = new EventManager();
        $service->setEventManager($em);

        $expected = array(
            'ZfcUser\Service\AbstractPluginService',
            'ZfcUserTest\Asset\PluginService'
        );

        $this->assertEquals($expected, $em->getIdentifiers());
    }

    /**
     * @covers \ZfcUser\Service\AbstractPluginService::registerPlugin
     * @covers \ZfcUser\Service\AbstractPluginService::getEventManager
     */
    public function testRegisterPlugin()
    {
        $service = new PluginService();
        $service->registerPlugin(new LoginListener());

        $this->setExpectedException('ZfcUser\Service\Exception\InvalidPluginException');
        $service->registerPlugin(new RegisterListener());
    }
}
