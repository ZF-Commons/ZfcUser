<?php

namespace ZfcUser\Form;

use Zend\Validator\Csrf as CsrfValidator;
use ZfcBase\InputFilter\ProvidesEventsInputFilter;

class LogoutFilter extends ProvidesEventsInputFilter
{
    public function __construct()
    {
        $csrfValidator = new CsrfValidator(array('name' => 'csrf', 'timeout' => session_cache_expire()));

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
