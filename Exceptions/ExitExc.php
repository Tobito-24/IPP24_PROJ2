<?php

namespace IPP\Student\Exceptions;

use Exception;

class ExitExc extends Exception
{
    public function __construct(string $message = "", int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    public function setCode(int $code) : void
    {
        $this->code = $code;
    }
}