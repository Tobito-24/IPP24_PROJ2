<?php

namespace IPP\Student\Exceptions;

use IPP\Core\Exception\IPPException;

class InvalidXMLStructure extends IPPException
{
    public function __construct(string $message = "Invalid XML structure", int $code = 32) {
        parent::__construct($message, $code);
    }
}