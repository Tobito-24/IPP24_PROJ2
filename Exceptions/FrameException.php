<?php

namespace IPP\Student\Exceptions;

use Exception;
use IPP\Core\Exception\IPPException;

class FrameException extends IPPException
{
    public function __construct(string $message = "Not existing frame", int $code = 55) {
        parent::__construct($message, $code);
    }
}