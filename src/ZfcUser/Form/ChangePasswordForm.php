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
     * @var bool
     */
    protected $initialized = false;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        /**
         * This is needed as ZF2 runs init() on every call for a shared form
         * See: https://github.com/zendframework/zf2/pull/6132
         */
        if ($this->initialized) {
            return;
        }

        $this->initialized = true;

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
