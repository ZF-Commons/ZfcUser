<?php

namespace ZfcUser\Model;

interface UserMetaMapperInterface
{
    public function add(UserMeta $userMeta);

    public function update(UserMeta $userMeta);

    public function get($userId, $metaKey);
}
