<?php

namespace ZfcUser\Model;

interface UserMetaMapperInterface
{
    public function add(UserMetaInterface $userMeta);

    public function update(UserMetaInterface $userMeta);

    public function get($userId, $metaKey);
}
