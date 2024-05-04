<?php

namespace IPP\Student\Instructions;

use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Exceptions\TypeException;

class Lt extends CompOp
{
    public function doExecute(mixed $symb1value, mixed $symb2value): bool
    {
            return $symb1value < $symb2value;
    }
}