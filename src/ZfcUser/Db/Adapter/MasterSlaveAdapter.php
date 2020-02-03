<?php
namespace ZfcBase\Db\Adapter;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Platform;
use Laminas\Db\ResultSet;

class MasterSlaveAdapter extends Adapter implements MasterSlaveAdapterInterface
{
    /**
     * slave adapter
     *
     * @var Adapter
     */
    protected $slaveAdapter;
    /**
     * @param Adapter $slaveAdapter
     * @param Driver\DriverInterface|array $driver
     * @param Platform\PlatformInterface $platform
     * @param ResultSet\ResultSet $queryResultPrototype
     */
    public function __construct(
        Adapter $slaveAdapter,
        $driver,
        Platform\PlatformInterface $platform = null,
        ResultSet\ResultSetInterface $queryResultPrototype = null
    ) {
        $this->slaveAdapter = $slaveAdapter;
        parent::__construct($driver, $platform, $queryResultPrototype);
    }
    /**
     * get slave adapter
     *
     * @return Adapter
     */
    public function getSlaveAdapter()
    {
        return $this->slaveAdapter;
    }
}
