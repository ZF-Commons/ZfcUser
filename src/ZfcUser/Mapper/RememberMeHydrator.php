<?php

namespace ZfcUser\Mapper;

use Zend\Stdlib\Hydrator\ClassMethods;

class RememberMeHydrator extends ClassMethods
{
    public function extract($object)
    {
        $data = parent::extract($object);
        return $data;
    }

    public function hydrate(array $data, $object)
    {
        return parent::hydrate($data, $object);
    }
}
