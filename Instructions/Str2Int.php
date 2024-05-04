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

class Str2Int extends VariableTwoSymbols
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
        if($symbol1->getType() != DataTypeEnum::string || $symbol2->getType() != DataTypeEnum::int) //check the types
        {
            throw new TypeException;
        }
        $string = $symbol1->getValue();
        $index = $symbol2->getValue();
        if(gettype($string) !== 'string' || gettype($index) !== 'integer' || $string == null) //check the values
        {
            throw new TypeException;
        }
        if($index < 0 || $index >= mb_strlen($string)) //check the index
        {
            throw new StringException;
        }
        $char = mb_substr($string, $index, 1); //get the character
        $ordValue = mb_ord($char); //get the ASCII value
        $variable->setType(DataTypeEnum::int); //set the type
        $variable->setValue($ordValue); //set the value
    }
}