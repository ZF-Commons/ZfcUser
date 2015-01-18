<?php

namespace ZfcUserTest\Authentication\Listener;

use ZfcUser\Authentication\Listener\RegenerateSessionIdentifier;

class RegenerateSessionIdentifierTest extends \PHPUnit_Framework_TestCase
{
    public function testOperation()
    {
        $manager = $this->getMock('Zend\Session\SessionManager');
        $manager->expects($this->once())
                ->method('regenerateId');
        
        $listener = new RegenerateSessionIdentifier($manager);
        $listener();
    }
}
