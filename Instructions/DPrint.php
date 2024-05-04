<?php

namespace IPP\Student\Instructions;

use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\MissingValueException;
use IPP\Student\Exceptions\NotExistException;
use IPP\Student\Exceptions\TypeException;
use IPP\Student\IO;
use IPP\Student\Operands\Symbol;

class DPrint implements InstructionInterface
{
    private IO $IO;
    private string $symbolString;
    private DataTypeEnum $symbolType;
    public function __construct(string $symbolString, DataTypeEnum $symbolType, IO $IO)
    {
        $this->symbolString = $symbolString;
        $this->symbolType = $symbolType;
        $this->IO = $IO;
    }

    /**
     * @throws FrameException
     * @throws NotExistException
     * @throws TypeException
     * @throws InvalidXMLStructure
     */
    public function execute() : void
    {
        $symbol = Symbol::GetSymbol($this->symbolType, $this->symbolString);
        if(gettype($symbol->getValue()) != "string" && gettype($symbol->getValue()) != "integer" && gettype($symbol->getValue()) != "boolean")
        {
            throw new TypeException;
        }
        $this->IO->writeStderr(strval($symbol->getValue()));
    }
}