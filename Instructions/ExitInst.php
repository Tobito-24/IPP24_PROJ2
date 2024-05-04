<?php

namespace IPP\Student\Instructions;

use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Exceptions\ExitExc;
use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\MissingValueException;
use IPP\Student\Exceptions\NotExistException;
use IPP\Student\Exceptions\TypeException;
use IPP\Student\Operands\Symbol;

class ExitInst implements InstructionInterface
{
    private string $symbolName;
    private DataTypeEnum $symbolType;

    public function __construct(string $symbolName, DataTypeEnum $symbolType)
    {
        $this->symbolName = $symbolName;
        $this->symbolType = $symbolType;
    }

    /**
     * @throws TypeException
     * @throws NotExistException
     * @throws FrameException
     * @throws ExitExc
     * @throws InvalidXMLStructure
     */
    public function execute(): void
    {
        $symbol = Symbol::GetSymbol($this->symbolType, $this->symbolName);
        $type = $symbol->getType();
        if($type == DataTypeEnum::int) // check if the type of the symbol is int
        {
            if(gettype($symbol->getValue()) != "integer") // check if the value of the symbol is integer
            {
                throw new TypeException; // if not, throw exception
            }
            else
            {
                $e = new ExitExc(); // if it is, throw exit exception
                $e->setCode($symbol->getValue()); // set the code of the exception to the value of the symbol
                throw $e; // throw the exception
            }
        }
        else // if the type of the symbol is not int throw an exception
        {
            throw new TypeException;
        }

    }
}