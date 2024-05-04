<?php

namespace IPP\Student\Instructions;

use IPP\Student\Exceptions\BadValueException;

class Idiv extends ArithmeticOp
{
    /**
     * @throws BadValueException
     */
    public function doExecute(int $symb1, int $symb2): int
    {
        if($symb2 == 0) //if dividing by zero throw exception
        {
            throw new BadValueException;
        }
        return intdiv($symb1, $symb2);
    }
}