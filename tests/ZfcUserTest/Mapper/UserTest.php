<?php

namespace ZfcUserTest\Mapper;

use ZfcUser\Mapper\User as Mapper;

class UserTest extends \PHPUnit_Framework_TestCase
{
    protected $mapper;

    public function setUp()
    {
        $mapper = new Mapper;
        $this->mapper = $mapper;
    }

    public function testFindByEmail()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testFindByUsername()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testFindById()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetTableName()
    {
        $this->assertEquals('user', $this->mapper->getTableName());
    }

    public function testSetTableName()
    {
        $this->mapper->setTableName('ZfcUser');
        $this->assertEquals('ZfcUser', $this->mapper->getTableName());
    }

    public function testInsert()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testUpdate()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
