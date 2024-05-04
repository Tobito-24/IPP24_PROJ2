<?php

namespace IPP\Student\Exceptions;

use Exception;
use IPP\Core\Exception\IPPException;

class StringException extends IPPException
{
    public function __construct(string $message = "String exception", int $code = 58) {
        parent::__construct($message, $code);
    }
}