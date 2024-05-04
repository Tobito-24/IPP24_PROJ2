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

class Strlen extends VarSymbol
{
    /**
     * @throws TypeException
     * @throws NotExistException
     * @throws FrameException
     * @throws InvalidXMLStructure
     * @throws MissingValueException
     */
    public function execute() : void
    {
        $variable = Variable::GetVariable($this->name);
        $symbol = Symbol::GetSymbol($this->symbType, $this->symbolString);
        if($symbol->GetType() == DataTypeEnum::string) //check if the type of the symbol is string
        {
            if(gettype($symbol->getValue()) == "string")
            {
                //strlen() returns the length of the string and sets it as the value of the variable
                $variable->setValue(strlen($symbol->getValue()));
                $variable->setType(DataTypeEnum::int); //set the type of the variable to int
            }
            else
            {
                throw new TypeException();
            }
        }
        else
        {
            throw new TypeException();
        }
    }
}