<?php

namespace ZfcUser\Service\Exception;

use ZfcUser\Exception;

class InvalidPluginException extends Exception\InvalidArgumentException
    implements ExceptionInterface
{

}
