<?php

namespace IPP\Student\Instructions;

use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\MissingValueException;
use IPP\Student\Exceptions\NotExistException;
use IPP\Student\Exceptions\StringException;
use IPP\Student\Exceptions\TypeException;
use IPP\Student\Operands\Literal;
use IPP\Student\Operands\Symbol;
use IPP\Student\Operands\Variable;

class IntToChar extends VarSymbol
{
    /**
     * @throws MissingValueException
     * @throws NotExistException
     * @throws StringException
     * @throws TypeException
     * @throws FrameException
     * @throws InvalidXMLStructure
     */
    public function execute() : void
    {
        $variable = Variable::GetVariable($this->name);
        $symbol = Symbol::GetSymbol($this->symbType, $this->symbolString);
        $symbType = $symbol->getType();
        if($symbType == DataTypeEnum::int)
        {
            if(gettype($symbol->getValue()) == "integer") // check if the value is integer
            {
                $value = mb_chr($symbol->getValue()); // convert integer to char
                if(!$value) // check if the value is valid
                {
                    throw new StringException; // throw exception if the value is not valid
                }
                $variable->setValue($value); // set the value of the variable
                $variable->setType(DataTypeEnum::string); // set the type of the variable
            }
            else
            {
                throw new TypeException;
            }

        }
        else
        {
            throw new TypeException;
        }
    }
}