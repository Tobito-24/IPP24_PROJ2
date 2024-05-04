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

class GetChar extends VariableTwoSymbols
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
        $variable = Variable::getVariable($this->var); // get variable
        $symbol1 = Symbol::GetSymbol($this->symbType1, $this->symb1);
        $symbol2 = Symbol::GetSymbol($this->symbType2, $this->symb2);
        if($symbol1->getType() != DataTypeEnum::string || $symbol2->getType() != DataTypeEnum::int) // check if types are correct
        {
            throw new TypeException;
        }
        $string = $symbol1->getValue();
        $index = $symbol2->getValue();
        if(gettype($string) !== 'string' || gettype($index) !== 'integer' || $string == null) // check if types are correct again
        {
            throw new TypeException;
        }
        if($index < 0 || $index >= mb_strlen($string)) // check if index is in range
        {
            throw new StringException; // if it is not, throw exception
        }
        $variable->setValue(mb_substr($string, $index, 1)); // set value of variable to character at index
        $variable->setType(DataTypeEnum::string); // set type of variable to string
    }

}