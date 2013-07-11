<?php

namespace ZfcUser\Extension\Exception;

use ZfcUser\Exception;

class MissingExtensionException extends Exception\InvalidArgumentException
    implements ExceptionInterface
{

}