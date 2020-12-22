<?php
namespace ZfcUser\Form;

use Laminas\EventManager\EventManagerAwareTrait;
use Laminas\Form\Form;

class ProvidesEventsForm extends Form
{
    use EventManagerAwareTrait;
}
