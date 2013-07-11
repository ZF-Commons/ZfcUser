<?php

namespace ZfcUser\Extension\Exception;

use ZfcUser\Exception;

class InvalidExtensionException extends Exception\InvalidArgumentException
    implements ExceptionInterface
{

}