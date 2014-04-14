<?php
namespace ZfcUser\Form;

use Zend\Form\Form;

/**
 * Class ChangePassword
 * @package ZfcUser\Form
 */
class ChangePasswordForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->add([
            'name' => 'credential',
            'type' => 'Password',
            'options' => [
                'label' => 'Current Password',
            ],
        ]);

        $this->add([
            'name' => 'newCredential',
            'type' => 'Password',
            'options' => [
                'label' => 'New Password',
            ],
        ]);

        $this->add([
            'name' => 'newCredentialVerify',
            'type' => 'Password',
            'options' => [
                'label' => 'Verify New Password',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'Button',
            'attributes' => [
                'type' => 'submit',
            ],
            'options' => [
                'label' => 'Submit',
            ],
        ]);
    }
}
