<?php

namespace ZfcUser\Extension\Exception;

use ZfcUser\Exception;

class InvalidUserException extends Exception\InvalidArgumentException
    implements ExceptionInterface
{

}