<?php

namespace ZfcUserTest\Mapper;

use ZfcUser\Mapper\User as Mapper;
use ZfcUser\Entity\User as Entity;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Adapter\Adapter;
use ZfcUser\Mapper\UserHydrator;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /** @var \ZfcUser\Mapper\User */
    protected $mapper;

    /** @var \Zend\Db\Adapter\Adapter */
    protected $mockedDbAdapter;

    /** @var \Zend\Db\Adapter\Adapter */
    protected $realAdapter = array();

    /** @var \Zend\Db\Sql\Select */
    protected $mockedSelect;

    /** @var \Zend\Db\ResultSet\HydratingResultSet */
    protected $mockedResultSet;

    /** @var \Zend\Db\Sql\Sql */
    protected $mockedDbSql;

    /** @var \Zend\Db\Adapter\Driver\DriverInterface */
    protected $mockedDbAdapterDriver;

    /** @var \Zend\Db\Adapter\Platform\PlatformInterface */
    protected $mockedDbAdapterPlatform;

    public function setUp()
    {
        $mapper = new Mapper;
        $mapper->setEntityPrototype(new Entity());
        $mapper->setHydrator(new UserHydrator());
        $this->mapper = $mapper;


        $this->setUpMockedAdapter();

        $this->mockedSelect = $this->getMock('\Zend\Db\Sql\Select', array('where'));

        $this->mockedResultSet = $this->getMock('\Zend\Db\ResultSet\HydratingResultSet');

        $this->setUpAdapter('mysql');
//         $this->setUpAdapter('pgsql');
        $this->setUpAdapter('sqlite');
    }

    /**
     *
     */
    public function setUpAdapter($driver)
    {
        $upCase = strtoupper($driver);
        if (!defined(sprintf('DB_%s_DSN', $upCase)) ||
            !defined(sprintf('DB_%s_USERNAME', $upCase)) ||
            !defined(sprintf('DB_%s_PASSWORD', $upCase)) ||
            !defined(sprintf('DB_%s_SCHEMA', $upCase))
        ) {
             return false;
        }

        try {
            $connection = array(
                'driver'=>sprintf('Pdo_%s', ucfirst($driver)),
                'dsn'=>constant(sprintf('DB_%s_DSN', $upCase))
            );
            if (constant(sprintf('DB_%s_USERNAME', $upCase)) !== "") {
                $connection['username'] = constant(sprintf('DB_%s_USERNAME', $upCase));
                $connection['password'] = constant(sprintf('DB_%s_PASSWORD', $upCase));
            }
            $adapter = new Adapter($connection);

            $this->setUpSqlDatabase($adapter, constant(sprintf('DB_%s_SCHEMA', $upCase)));

            $this->realAdapter[$driver] = $adapter;
        } catch (\Exception $e) {
            $this->realAdapter[$driver] = false;
        }
    }

    public function setUpSqlDatabase($adapter, $schemaPath)
    {
        $queryStack= array('DROP TABLE IF EXISTS user');
        $queryStack = array_merge($queryStack, explode(';', file_get_contents($schemaPath)));
        $queryStack = array_merge($queryStack, explode(';', file_get_contents(__DIR__ . '/_files/user.sql')));

        foreach ($queryStack as $query) {
            if (!preg_match('/\S+/', $query)) {
                continue;
            }
            $adapter->query($query, $adapter::QUERY_MODE_EXECUTE);
        }
    }

    /**
     *
     */
    public function setUpMockedAdapter()
    {
        $this->mockedDbAdapterDriver = $this->getMock('Zend\Db\Adapter\Driver\DriverInterface');
        $this->mockedDbAdapterPlatform = $this->getMock('\Zend\Db\Adapter\Platform\PlatformInterface', array());
        $this->mockedDbAdapterStatement= $this->getMock('\Zend\Db\Adapter\Driver\StatementInterface', array());

        $this->mockedDbAdapterPlatform->expects($this->any())
                                      ->method('getName')
                                      ->will($this->returnValue('null'));

        $this->mockedDbAdapter = $this->getMockBuilder('\Zend\Db\Adapter\Adapter')
                                      ->setConstructorArgs(array(
                                          $this->mockedDbAdapterDriver,
                                          $this->mockedDbAdapterPlatform
                                      ))
                                      ->getMock(array('getPlatform'));

        $this->mockedDbAdapter->expects($this->any())
                              ->method('getPlatform')
                              ->will($this->returnValue($this->mockedDbAdapterPlatform));

        $this->mockedDbSql = $this->getMockBuilder('\Zend\Db\Sql\Sql')
                                  ->setConstructorArgs(array($this->mockedDbAdapter))
                                  ->setMethods(array('prepareStatementForSqlObject'))
                                  ->getMock();
        $this->mockedDbSql->expects($this->any())
                          ->method('prepareStatementForSqlObject')
                          ->will($this->returnValue($this->mockedDbAdapterStatement));

        $this->mockedDbSqlPlatform = $this->getMockBuilder('\Zend\Db\Sql\Platform\Platform')
                                          ->setConstructorArgs(array($this->mockedDbAdapter))
                                          ->getMock();
    }

    /**
     *
     * @param arra $eventListenerArray
     * @return array
     */
    public function setUpMockMapperInsert($mapperMethods)
    {
        $this->mapper = $this->getMock(get_class($this->mapper), $mapperMethods);

        foreach ($mapperMethods as $method) {
            switch ($method) {
                case 'getSelect':
                    $this->mapper->expects($this->once())
                                 ->method('getSelect')
                                 ->will($this->returnValue($this->mockedSelect));
                    break;
                case 'initialize':
                    $this->mapper->expects($this->once())
                                 ->method('initialize')
                                 ->will($this->returnValue(true));
                    break;
            }
        }
    }

    /**
     *
     * @param arra $eventListenerArray
     * @return array
     */
    public function &setUpMockedMapper($eventListenerArray, array $mapperMethods = array())
    {
        $returnMockedParams = array();

        $mapperMethods = count($mapperMethods)
            ? array_merge($mapperMethods, array('getSelect', 'select'))
            : array('getSelect','select');

        $this->setUpMockMapperInsert($mapperMethods);

        $this->mapper->expects($this->once())
                     ->method('select')
                     ->will($this->returnValue($this->mockedResultSet));

        $mockedSelect = $this->mockedSelect;
        $this->mockedSelect->expects($this->once())
                           ->method('where')
                           ->will($this->returnCallback(function () use (&$returnMockedParams, $mockedSelect) {
                               $returnMockedParams['whereArgs'] = func_get_args();
                               return $mockedSelect;
                           }));

        foreach ($eventListenerArray as $eventKey => $eventListener) {
            $this->mapper->getEventManager()->attach($eventKey, $eventListener);
        }

        $this->mapper->setDbAdapter($this->mockedDbAdapter);
        $this->mapper->setEntityPrototype(new Entity());

        return $returnMockedParams;
    }

    /**
     * @dataProvider providerTestFindBy
     * @param string $methode
     * @param array $args
     * @param array $expectedParams
     */
    public function testFindBy($methode, $args, $expectedParams, $eventListener, $entityEqual)
    {
        $mockedParams =& $this->setUpMockedMapper($eventListener);

        $this->mockedResultSet->expects($this->once())
             ->method('current')
             ->will($this->returnValue($entityEqual));

        $return = call_user_func_array(array($this->mapper, $methode), $args);

        foreach ($expectedParams as $paramKey => $paramValue) {
            $this->assertArrayHasKey($paramKey, $mockedParams);
            $this->assertEquals($paramValue, $mockedParams[$paramKey]);
        }
        $this->assertEquals($entityEqual, $return);
    }

    /**
     * @todo Integration test for UserMapper
     * @dataProvider providerTestFindBy
     */
    public function testIntegrationFindBy($methode, $args, $expectedParams, $eventListener, $entityEqual)
    {
        /* @var $entityEqual Entity */
        /* @var $dbAdapter Adapter */
        foreach ($this->realAdapter as $dbAdapter) {
            if ($dbAdapter == false) {
                continue;
            }

            $this->mapper->setDbAdapter($dbAdapter);
            $return = call_user_func_array(array($this->mapper, $methode), $args);

            $this->assertInternalType('object', $return);
            $this->assertInstanceOf('ZfcUser\Entity\User', $return);
            $this->assertEquals($entityEqual, $return);
        }

        if (!isset($return)) {
            $this->markTestSkipped("Without real database we dont can test findByEmail / findByUsername / findById");
        }
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

    public function testInsertUpdateDelete()
    {
        $baseEntity = new Entity();
        $baseEntity->setEmail('zfc-user-foo@zend-framework.org');
        $baseEntity->setUsername('zfc-user-foo');
        $baseEntity->setPassword('zfc-user-foo');

        /* @var $entityEqual Entity */
        /* @var $dbAdapter Adapter */
        foreach ($this->realAdapter as $diver => $dbAdapter) {
            if ($dbAdapter === false) {
                continue;
            }
            $this->mapper->setDbAdapter($dbAdapter);

            // insert
            $entity = clone $baseEntity;

            $result = $this->mapper->insert($entity);

            $this->assertNotNull($entity->getId());
            $this->assertGreaterThanOrEqual(1, $entity->getId());

            $entityEqual = $this->mapper->findById($entity->getId());
            $this->assertEquals($entity, $entityEqual);

            // update
            $entity->setUsername($entity->getUsername() . '-' . $diver);
            $entity->setEmail($entity->getUsername() . '@github.com');

            $result = $this->mapper->update($entity);

            $entityEqual = $this->mapper->findById($entity->getId());
            $this->assertNotEquals($baseEntity->getUsername(), $entityEqual->getUsername());
            $this->assertNotEquals($baseEntity->getEmail(), $entityEqual->getEmail());

            $this->assertEquals($entity->getUsername(), $entityEqual->getUsername());
            $this->assertEquals($entity->getEmail(), $entityEqual->getEmail());

            /**
             *
             * @todo delete is currently protected

            // delete
            $result = $this->mapper->delete($entity->getId());

            $this->assertNotEquals($baseEntity->getEmail(), $entityEqual->getEmail());
            $this->assertEquals($entity->getEmail(), $entityEqual->getEmail());
             */
        }

        if (!isset($result)) {
            $this->markTestSkipped("Without real database we dont can test insert, update and delete");
        }
    }

    public function providerTestFindBy()
    {
        $user = new Entity();
        $user->setEmail('zfc-user@github.com');
        $user->setUsername('zfc-user');
        $user->setDisplayName('Zfc-User');
        $user->setId('1');
        $user->setState(1);
        $user->setPassword('zfc-user');

        return array(
            array(
                'findByEmail',
                array($user->getEmail()),
                array(
                    'whereArgs'=>array(
                        array('email'=>$user->getEmail()),
                        'AND'
                    )
                ),
                array(),
                $user
            ),
            array(
                'findByUsername',
                array($user->getUsername()),
                array(
                    'whereArgs'=>array(
                        array('username'=>$user->getUsername()),
                        'AND'
                    )
                ),
                array(),
                $user
            ),
            array(
                'findById',
                array($user->getId()),
                array(
                    'whereArgs'=>array(
                        array('user_id'=>$user->getId()),
                        'AND'
                    )
                ),
                array(),
                $user
            ),
        );
    }
}
