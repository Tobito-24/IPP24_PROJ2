<?php

namespace IPP\Student\Instructions;

use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\MissingValueException;
use IPP\Student\Exceptions\NotExistException;
use IPP\Student\Operands\Literal;
use IPP\Student\Operands\Symbol;
use IPP\Student\Stack;

class Pushs implements InstructionInterface
{
    private string $symbolString;
    private DataTypeEnum $symbolType;
    public function __construct(DataTypeEnum $symbolType, string $symbolString)
    {
        $this->symbolType = $symbolType;
        $this->symbolString = $symbolString;
    }

    /**
     * @throws FrameException
     * @throws NotExistException
     * @throws InvalidXMLStructure
     * @throws MissingValueException
     */
    public function execute() : void
    {
        $stack = Stack::getInstance();
        $symbol = Symbol::getSymbol($this->symbolType, $this->symbolString);
        $literal = Literal::getLiteralFromSymbol($symbol);
        $stack->push($literal);
    }
}