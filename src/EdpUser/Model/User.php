<?php

namespace EdpUser\Model;

use Doctrine\ORM\Mapping as ORM,
    EdpUser\ModelBase\UserBase;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends UserBase
{
}
