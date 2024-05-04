<?php

namespace IPP\Student\Instructions;

use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\MissingValueException;
use IPP\Student\Exceptions\NotExistException;
use IPP\Student\Exceptions\TypeException;
use IPP\Student\Operands\Symbol;
use IPP\Student\Operands\Variable;

class Concat extends VariableTwoSymbols
{
    /**
     * @throws NotExistException
     * @throws FrameException
     * @throws TypeException
     * @throws InvalidXMLStructure
     * @throws MissingValueException
     */
    public function execute():void
    {
        $variable = Variable::GetVariable($this->var);
        $symbol1 = Symbol::GetSymbol($this->symbType1, $this->symb1);
        $symbol2 = Symbol::GetSymbol($this->symbType2, $this->symb2);
        if($symbol1->getType() !== DataTypeEnum::string || $symbol2->getType() !== DataTypeEnum::string) //type check
        {
            throw new TypeException;
        }
        if(gettype($symbol1->getValue()) !== 'string' || gettype($symbol2->getValue()) !== 'string') //type check 2
        {
            throw new TypeException;
        }
        $variable->setValue($symbol1->getValue() . $symbol2->getValue()); //concatenate the two strings
        $variable->setType(DataTypeEnum::string); //set type
    }
}