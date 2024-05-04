<?php

namespace IPP\Student\Exceptions;

use Exception;
use IPP\Core\Exception\IPPException;

class NotExistException extends IPPException
{
    public function __construct(string $message = "Variable doesn't exist", int $code = 54) {
        parent::__construct($message, $code);
    }
}