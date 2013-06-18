<?php

namespace ZfcUserTest\Options;

use ZfcUser\Options\ModuleOptions;

class ModuleOptionsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider optionsProvider
     */
    public function testOptions($key, $value)
    {
        $setter  = 'set' . ucfirst($key);
        $getter  = 'get' . ucfirst($key);

        $options = new ModuleOptions();
        $result  = $options->$setter($value)
                           ->$getter($value);

        $this->assertEquals($value, $result);
    }

    public function optionsProvider()
    {
        return array(
            array('entityClass', 'string'),
            array('authenticationService', 'string'),
            array('registerPlugins', array('test')),
            array('loginPlugins', array('test')),
            array('loginAdapters', array('test')),
            array('registerHydrator', 'string'),
            array('passwordCost', 10),
            array('passwordSalt', 'string')
        );
    }
}