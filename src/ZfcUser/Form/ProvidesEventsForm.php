<?php
namespace ZfcUser\Form;

use Zend\EventManager\EventManagerAwareTrait;
use Zend\Form\Form;

class ProvidesEventsForm extends Form
{
    use EventManagerAwareTrait;
}
