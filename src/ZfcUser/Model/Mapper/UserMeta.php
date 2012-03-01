<?php

namespace ZfcUser\Model\Mapper;

interface UserMeta
{
    public function add($userMeta);

    public function update($userMeta);

    public function get($userId, $metaKey);
}
