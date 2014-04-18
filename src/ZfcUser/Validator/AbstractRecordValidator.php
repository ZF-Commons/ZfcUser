<?php
namespace ZfcUser\Validator;

use Exception as PhpException;
use Zend\Validator\AbstractValidator;
use ZfcUser\Mapper\UserInterface as UserMapperInterface;

/**
 * Class AbstractRecordValidator
 * @package ZfcUser\Validator
 */
abstract class AbstractRecordValidator extends AbstractValidator
{
    /**
     * Error constants
     */
    const ERROR_NO_RECORD_FOUND = 'noRecordFound';
    const ERROR_RECORD_FOUND    = 'recordFound';

    /**
     * @var array Message templates
     */
    protected $messageTemplates = array(
        self::ERROR_NO_RECORD_FOUND => 'No record matching the input was found',
        self::ERROR_RECORD_FOUND    => 'A record matching the input was found',
    );

    /**
     * @var UserMapperInterface
     */
    protected $mapper;

    /**
     * @var string
     */
    protected $key;

    /**
     * Required options are:
     *  - key: Field to use, 'email' or 'username'
     */
    public function __construct(array $options, UserMapperInterface $mapper)
    {
        if (!array_key_exists('key', $options)) {
            throw new Exception\InvalidArgumentException('No key provided');
        }

        $this->key      = $options['key'];
        $this->mapper   = $mapper;

        parent::__construct($options);
    }

    /**
     * Grab the user from the mapper
     *
     * @param   string $value
     * @throws  PhpException
     * @return  mixed
     */
    protected function query($value)
    {
        switch ($this->key) {
            case 'email':
                $result = $this->mapper->findByEmail($value);
                break;

            case 'username':
                $result = $this->mapper->findByUsername($value);
                break;

            default:
                throw new PhpException('Invalid key used');
        }

        return $result;
    }
}
