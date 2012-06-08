<?php

namespace ZfcUser\Mapper;

use ZfcBase\Mapper\DataMapperInterface;

interface UserMetaInterface extends DataMapperInterface
{
    public function get($userId, $metaKey);
}
