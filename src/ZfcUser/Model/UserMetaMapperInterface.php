<?php

namespace ZfcUser\Model\Mapper;

use ZfcUser\Model\UserMeta as UserMetaModel;

interface UserMeta
{
    public function add(UserMetaModel $userMeta);

    public function update(UserMetaModel $userMeta);

    public function get($userId, $metaKey);
}
