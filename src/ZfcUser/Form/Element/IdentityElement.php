<?php
namespace ZfcUser\Form\Element;

use Zend\Form\Element\Text;
use ZfcUser\Options\AuthenticationOptionsInterface;

/**
 * Class IdentityElement
 * @package ZfcUser\Form\Element
 */
class IdentityElement extends Text
{
    /**
     * @var AuthenticationOptionsInterface
     */
    protected $authenticationOptions;

    /**
     * @param null|int|string                   $name    Optional name for the element
     * @param AuthenticationOptionsInterface    $authenticationOptions
     */
    public function __construct($name = null, AuthenticationOptionsInterface $authenticationOptions)
    {
        $this->authenticationOptions = $authenticationOptions;
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions($options)
    {
        parent::setOptions($options);

        $identityFields = $this->authenticationOptions->getAuthIdentityFields();

        if (count($identityFields) === 1) {
            $this->setLabel(ucfirst($identityFields[0]));

            if ($identityFields[0] === 'email') {
                $this->setAttribute('type', 'email');
            }

            return;
        }

        $label = '';

        foreach ($identityFields as $field) {
            $label .= (empty($label)) ? ucfirst($field) : ' or '. ucfirst($field);
        }

        $this->setLabel($label);
    }
}