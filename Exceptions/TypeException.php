<?php

namespace IPP\Student\Exceptions;

use Exception;
use IPP\Core\Exception\IPPException;

class TypeException extends IPPException
{
    public function __construct(string $message = "Wrong operand types", int $code = 53) {
        parent::__construct($message, $code);
    }
}