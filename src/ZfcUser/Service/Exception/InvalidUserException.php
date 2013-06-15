<?php

namespace ZfcUser\Service\Exception;

use ZfcUser\Exception;

class InvalidUserException extends Exception\InvalidArgumentException
    implements ExceptionInterface
{

}
