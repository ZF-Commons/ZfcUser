<?php
namespace ZfcUser\InputFilter;

use Zend\InputFilter\InputFilter;
use ZfcUser\Options\AuthenticationOptionsInterface;

/**
 * Class LoginFilter
 * @package ZfcUser\InputFilter
 */
class LoginFilter extends InputFilter
{
    /**
     * @var AuthenticationOptionsInterface
     */
    protected $options;

    /**
     * @param AuthenticationOptionsInterface $options
     */
    public function __construct(AuthenticationOptionsInterface $options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->add([
            'name' => 'identity',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StringTrim',
                ],
            ],
        ]);

        $this->add([
            'name' => 'credential',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StringTrim',
                ],
            ],
        ]);
    }
}
