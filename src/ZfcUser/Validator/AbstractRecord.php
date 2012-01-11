<?php

namespace ZfcUser\Validator;

use Zend\Validator\AbstractValidator,
    ZfcUser\Mapper\UserInterface as UserMapper;

abstract class AbstractRecord extends AbstractValidator
{
    /**
     * Error constants
     */
    const ERROR_NO_RECORD_FOUND = 'noRecordFound';
    const ERROR_RECORD_FOUND    = 'recordFound';

    /**
     * @var array Message templates
     */
    protected $_messageTemplates = array(
        self::ERROR_NO_RECORD_FOUND => "No record matching '%value%' was found",
        self::ERROR_RECORD_FOUND    => "A record matching '%value%' was found",
    );

    /**
     * @var UserMapper
     */
    protected $mapper;

    /**
     * @var string
     */
    protected $key;

    /**
     * Required options are:
     *  - key     Field to use, 'emial' or 'username'
     */
    public function __construct(array $options)
    {
        if (!array_key_exists('key', $options)) {
            throw new Exception\InvalidArgumentException('No key provided');
        }
        
        $this->setKey($options['key']);
        
        parent::__construct($options);
    }

    /**
     * getMapper 
     * 
     * @return UserMapper
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * setMapper 
     * 
     * @param UserMapper $mapper 
     * @return Db
     */
    public function setMapper(UserMapper $mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }

    /**
     * Get key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
 
    /**
     * Set key.
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Grab the user from the mapper
     * 
     * @param string $value
     * @return mixed
     */
    protected function query($value)
    {
        $result = false;

        switch ($this->getKey()) {
            case 'email':
                $result = $this->getMapper()->findByEmail($value);
                break; 

            case 'username':
                $result = $this->getMapper()->findByUsername($value);
                break;

            default:
                throw new \Exception('Invalid key used in ZfcUser validator');
                break;
        }

        return $result;
    }
}
