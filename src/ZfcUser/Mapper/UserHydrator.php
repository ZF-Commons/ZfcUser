<?php

namespace ZfcUser\Mapper;

use Zend\Crypt\Password\PasswordInterface as ZendCryptPassword;
use Zend\Stdlib\Hydrator\ClassMethods;
use ZfcUser\Entity\UserInterface as UserEntity;

class UserHydrator extends ClassMethods implements HydratorInterface
{
    /**
     * @var ZendCryptPassword
     */
    private $cryptoService;

    /**
     * @param ZendCryptPassword $cryptoService
     * @param bool|array        $underscoreSeparatedKeys
     */
    public function __construct(
        ZendCryptPassword $cryptoService,
        $underscoreSeparatedKeys = true
    ) {
        parent::__construct($underscoreSeparatedKeys);
        $this->cryptoService = $cryptoService;
    }

    /**
     * Extract values from an object
     *
     * @param  UserEntityInterface $object
     * @return array
     * @throws Exception\InvalidArgumentException
     */
    public function extract($object)
    {
        $this->guardUserObject($object);
        $data = parent::extract($object);
        return $this->mapField('id', 'user_id', $data);
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array               $data
     * @param  UserEntityInterface $object
     * @return UserEntityInterface
     * @throws Exception\InvalidArgumentException
     */
    public function hydrate(array $data, $object)
    {
        $this->guardUserObject($object);
        $data = $this->mapField('user_id', 'id', $data);
        return parent::hydrate($data, $object);
    }

    /**
     * @return ZendCryptPassword
     */
    public function getCryptoService()
    {
        return $this->cryptoService;
    }

    /**
     * Remap an array key
     *
     * @param  string $keyFrom
     * @param  string $keyTo
     * @param  array  $array
     * @return array
     */
    protected function mapField($keyFrom, $keyTo, array $array)
    {
        if (isset($array[$keyFrom])) {
            $array[$keyTo] = $array[$keyFrom];
        }
        unset($array[$keyFrom]);
        return $array;
    }

    /**
     * Ensure $object is an UserEntityInterface
     *
     * @param  mixed $object
     * @throws Exception\InvalidArgumentException
     */
    protected function guardUserObject($object)
    {
        if (!$object instanceof UserEntity) {
            throw new Exception\InvalidArgumentException(
                '$object must be an instance of ZfcUser\Entity\UserInterface'
            );
        }
    }
}
