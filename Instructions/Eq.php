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

class Eq extends VariableTwoSymbols
{
    /**
     * @throws NotExistException
     * @throws TypeException
     * @throws FrameException
     * @throws InvalidXMLStructure
     * @throws MissingValueException
     */
    public function execute() : void
    {
        $variable = Variable::getVariable($this->var);
        $variable->setType(DataTypeEnum::bool);
        $symb1 = Symbol::getSymbol($this->symbType1 ,$this->symb1);
        $symb2 = Symbol::getSymbol($this->symbType2 ,$this->symb2);
        $symb1value = $symb1->getValue();
        $symb2value = $symb2->getValue();
        $symb1type = $symb1->getType();
        $symb2type = $symb2->getType();
        if($symb1type != $symb2type && ($symb1type != DataTypeEnum::nil && $symb2type != DataTypeEnum::nil)) //Type control
        {
            throw new TypeException();
        }
        if($symb1type == DataTypeEnum::nil || $symb2type == DataTypeEnum::nil) //If one of the operands is nil
        {
            if($symb1type == $symb2type) //If both are nil
            {
                $variable->setValue(true); //Both are nil, so they are equal
            }
            else
            {
                $variable->setValue(false); //One of them is nil, so they are not equal
            }
        }
        else if($symb1type == DataTypeEnum::bool || $symb1type == DataTypeEnum::int || $symb1type == DataTypeEnum::string)
        {
            if($symb1type == $symb2type) //If both are the same type
            {
                 $variable->setValue($symb1value == $symb2value); //Compare the values
            }
            else
            {
                throw new TypeException(); //If they are not the same type, throw exception
            }
        }
        else
        {
            throw new TypeException(); //If the type is not bool, int or string, throw exception
        }
    }
}