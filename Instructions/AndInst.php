<?php

namespace IPP\Student\Instructions;

class AndInst extends LogicalOp
{
    public function doExecute(bool $symb1value, bool $symb2value): bool
    {
        return $symb1value && $symb2value;
    }
}