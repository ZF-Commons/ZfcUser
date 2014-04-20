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
     * {@inheritdoc}
     */
    public function init()
    {
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
