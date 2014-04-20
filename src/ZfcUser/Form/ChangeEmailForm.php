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
            'type' => 'Email',
            'options' => [
                'label' => 'New Email',
            ],
        ]);

        $this->add([
            'name' => 'newIdentityVerify',
            'type' => 'Email',
            'options' => [
                'label' => 'Verify New Email',
            ],
        ]);

        $this->add([
            'name' => 'credential',
            'type' => 'Password',
            'options' => [
                'label' => 'Password',
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
