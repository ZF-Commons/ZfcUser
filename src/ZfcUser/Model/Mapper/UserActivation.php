<?php
namespace ZfcUser\Model\Mapper;

use ZfcUser\Model\UserActivation as UserActivationModel;

interface UserActivation
{
    public function persist(UserActivationModel $userActivation);

    public function findByEmail($email);

    public function findByToken($token);
}