<?php

namespace ZfcUser\Mapper;

use ZfcUser\Model\UserMetaInterface as UserMetaModelInterface;

interface UserMetaInterface
{
    public function add(UserMetaModelInterface $userMeta);

    public function update(UserMetaModelInterface $userMeta);

    public function get($userId, $metaKey);
}
