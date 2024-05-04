<?php

namespace IPP\Student\Instructions;

use IPP\Student\Enums\DataTypeEnum;

abstract class VariableTwoSymbols implements InstructionInterface //abstract class for instructions with variable and two symbols
{
    protected string $var;
    protected string $symb1;
    protected string $symb2;
    protected DataTypeEnum $symbType1;
    protected DataTypeEnum $symbType2;

    public function __construct(string $var, string $symb1, DataTypeEnum $symbType1, string $symb2,DataTypeEnum $symbType2)
    {
        $this->var = $var;
        $this->symb1 = $symb1;
        $this->symb2 = $symb2;
        $this->symbType1 = $symbType1;
        $this->symbType2 = $symbType2;
    }
}