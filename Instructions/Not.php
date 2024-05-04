<?php

namespace IPP\Student\Instructions;

use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\MissingValueException;
use IPP\Student\Exceptions\NotExistException;
use IPP\Student\Exceptions\TypeException;
use IPP\Student\Operands\Literal;
use IPP\Student\Operands\Symbol;
use IPP\Student\Operands\Variable;

class Not extends VarSymbol
{
    /**
     * @throws MissingValueException
     * @throws NotExistException
     * @throws TypeException
     * @throws FrameException
     * @throws InvalidXMLStructure
     */
    public function execute() : void
    {
        $variable = Variable::getVariable($this->name);
        $symbol = Symbol::GetSymbol($this->symbType, $this->symbolString);
        if($symbol->getType() != DataTypeEnum::bool) // if symbol is not bool throw and exception
        {
            throw new TypeException;
        }
        $variable->setValue(!$symbol->getValue()); // set the value of the variable to the opposite of the symbol
        $variable->setType(DataTypeEnum::bool); // set the type of the variable to bool
    }
}