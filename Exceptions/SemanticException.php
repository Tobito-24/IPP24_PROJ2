<?php

namespace IPP\Student\Exceptions;

use Exception;
use IPP\Core\Exception\IPPException;

class SemanticException extends IPPException
{
    public function __construct(string $message = "Semantic error", int $code = 52) {
        parent::__construct($message, $code);
    }
}