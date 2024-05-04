<?php

namespace IPP\Student\Exceptions;

use Exception;
use IPP\Core\Exception\IPPException;

class MissingValueException extends IPPException
{
    public function __construct(string $message = "Missing value", int $code = 56) {
        parent::__construct($message, $code);
    }
}