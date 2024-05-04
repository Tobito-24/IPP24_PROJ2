<?php

namespace IPP\Student\Exceptions;

use Exception;
use IPP\Core\Exception\IPPException;

class BadValueException extends IPPException
{
    public function __construct(string $message = "Bad value of an operand", int $code = 57) {
        parent::__construct($message, $code);
    }
}