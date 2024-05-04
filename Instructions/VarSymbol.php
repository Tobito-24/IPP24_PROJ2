<?php

namespace IPP\Student\Instructions;

use IPP\Student\Enums\DataTypeEnum;

abstract class VarSymbol implements InstructionInterface // and abstract class for instructions that use a variable and a symbol
{
    protected string $name;
    protected string $symbolString;
    protected DataTypeEnum $symbType;

    public function __construct(string $name, string $symbolString, DataTypeEnum $symbType)
    {
        $this->name = $name;
        $this->symbolString = $symbolString;
        $this->symbType = $symbType;
    }
}