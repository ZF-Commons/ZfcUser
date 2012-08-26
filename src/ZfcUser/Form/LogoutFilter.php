<?php

namespace ZfcUser\Form;

use Zend\Validator\Csrf as CsrfValidator;
use ZfcBase\InputFilter\ProvidesEventsInputFilter;

class LogoutFilter extends ProvidesEventsInputFilter
{
    public function __construct()
    {
        // Allow CSRF to timeout with session. Csrf element/validator uses 300 by default.
        $sessionLifetime = ini_get("session.gc_maxlifetime");
        $csrfValidator = new CsrfValidator(array('name' => 'csrf', 'timeout' => $sessionLifetime));

        $this->add(array(
            'name' => 'csrf',
            'required' => true,
            'filters' => array(
                array('name' => 'Zend\Filter\StringTrim'),
            ),
            'validators' => array(
                $csrfValidator,
            ),
        ));

        $this->getEventManager()->trigger('init', $this);
    }
}
