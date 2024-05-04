<?php

namespace IPP\Student\Instructions;

use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Operands\Literal;
use IPP\Student\Operands\Variable;

class Add extends ArithmeticOp
{
    public function doExecute(int $symb1, int $symb2): int
    {
        return $symb1 + $symb2;
    }
}