<?php

namespace ZfcUser\Mapper;

use Zend\Stdlib\Hydrator\ClassMethods;
use ZfcUser\Entity\UserInterface as UserEntityInterface;

class UserHydrator extends ClassMethods
{
    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     * @throws Exception\InvalidArgumentException
     */
    public function extract($object)
    {
        if (!$object instanceof UserEntityInterface) {
            throw new Exception\InvalidArgumentException('$object must be an instance of ZfcUser\Entity\UserInterface');
        }
        /* @var $object UserInterface*/
        $data = parent::extract($object);
        $this->mapFieldForExtract($data);
        return $data;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @return UserInterface
     * @throws Exception\InvalidArgumentException
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof UserEntityInterface) {
            throw new Exception\InvalidArgumentException('$object must be an instance of ZfcUser\Entity\UserInterface');
        }
        $this->mapFieldForHydrate($data);
        return parent::hydrate($data, $object);
    }

    protected function mapField($keyFrom, $keyTo, array &$array)
    {
        if ( !isset($array[$keyFrom]) && isset($array[$keyTo]) ) {
            return $array;
        }
        $array[$keyTo] = $array[$keyFrom];
        unset($array[$keyFrom]);
    }

    protected function mapFieldForExtract( array &$array )
    {
        $this->mapField('id', 'user_id', $array);
    }

    protected function mapFieldForHydrate( array &$array )
    {
        $this->mapField('user_id','id', $array);
    }
}
