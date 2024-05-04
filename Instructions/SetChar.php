<?php

namespace IPP\Student\Instructions;

use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\MissingValueException;
use IPP\Student\Exceptions\NotExistException;
use IPP\Student\Exceptions\StringException;
use IPP\Student\Exceptions\TypeException;
use IPP\Student\Operands\Symbol;
use IPP\Student\Operands\Variable;

class SetChar extends VariableTwoSymbols
{
    /**
     * @throws MissingValueException
     * @throws NotExistException
     * @throws TypeException
     * @throws FrameException
     * @throws StringException
     * @throws InvalidXMLStructure
     */
    public function execute() : void
    {
        $variable = Variable::getVariable($this->var);
        $symbol1 = Symbol::GetSymbol($this->symbType1, $this->symb1);
        $symbol2 = Symbol::GetSymbol($this->symbType2, $this->symb2);
        if($symbol2->getType() != DataTypeEnum::string || $symbol1->getType() != DataTypeEnum::int || $variable->getType() != DataTypeEnum::string) //check the types
        {
            throw new TypeException;
        }
        $string = $variable->getValue();
        $char = $symbol2->getValue();
        $index = $symbol1->getValue();
        if(gettype($string) != "string" || gettype($char) != "string" || gettype($index) != "integer") //check the types
        {
            throw new TypeException;
        }
        if($index < 0 || $index >= strlen($string)) //check the index
        {
            throw new StringException;
        }
        $string[$index] = $char[0]; //set the char
        $variable->setValue($string); //set the new value
    }
}