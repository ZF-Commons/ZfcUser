<?php
namespace ZfcUser\Form;

use Zend\Form\Form;
use ZfcUser\Options\AuthenticationOptionsInterface;

/**
 * Class ChangePassword
 * @package ZfcUser\Form
 */
class ChangePassword extends Form
{
    /**
     * @var AuthenticationOptionsInterface
     */
    protected $authOptions;

    /**
     * @param null|int|string $name Optional name for the element
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->add(array(
            'name' => 'credential',
            'options' => array(
                'label' => 'Current Password',
            ),
            'attributes' => array(
                'type' => 'password',
            ),
        ));

        $this->add(array(
            'name' => 'newCredential',
            'options' => array(
                'label' => 'New Password',
            ),
            'attributes' => array(
                'type' => 'password',
            ),
        ));

        $this->add(array(
            'name' => 'newCredentialVerify',
            'options' => array(
                'label' => 'Verify New Password',
            ),
            'attributes' => array(
                'type' => 'password',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Submit',
                'type'  => 'submit'
            ),
        ));
    }
}
