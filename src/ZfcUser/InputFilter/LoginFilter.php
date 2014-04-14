<?php
namespace ZfcUser\InputFilter;

use Zend\InputFilter\InputFilter;

/**
 * Class LoginFilter
 * @package ZfcUser\InputFilter
 */
class LoginFilter extends InputFilter
{
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
