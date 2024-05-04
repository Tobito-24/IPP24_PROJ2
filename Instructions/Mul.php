<?php

namespace IPP\Student\Instructions;

class Mul extends ArithmeticOp
{
    public function doExecute(int $symb1, int $symb2): int
    {
        return $symb1 * $symb2;
    }

}