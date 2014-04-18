<?php
namespace ZfcUser\Form;

use Zend\Form\Form;

/**
 * Class ChangeEmailForm
 * @package ZfcUser\Form
 */
class ChangeEmailForm extends Form
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
         * This is needed as ZF2 runs init() on every call for a shared form (prior to 2.3.1)
         * See: https://github.com/zendframework/zf2/pull/6132
         */
        if ($this->initialized) {
            return;
        }

        $this->initialized = true;

        $this->add([
            'name' => 'newIdentity',
            'options' => [
                'label' => 'New Email',
            ],
            'attributes' => [
                'type' => 'text',
            ],
        ]);

        $this->add([
            'name' => 'newIdentityVerify',
            'options' => [
                'label' => 'Verify New Email',
            ],
            'attributes' => [
                'type' => 'text',
            ],
        ]);

        $this->add([
            'name' => 'credential',
            'options' => [
                'label' => 'Password',
            ],
            'attributes' => [
                'type' => 'password',
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
