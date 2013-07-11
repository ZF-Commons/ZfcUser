<?php

namespace ZfcUser\Extension\Exception;

use ZfcUser\Exception;

class InvalidOptionException extends Exception\InvalidArgumentException
    implements ExceptionInterface
{

}