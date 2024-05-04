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

abstract class CompOp extends VariableTwoSymbols //Used for most comparing operations
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
        $symb1 = Symbol::getSymbol($this->symbType1 ,$this->symb1);
        $symb2 = Symbol::getSymbol($this->symbType2 ,$this->symb2);
        $symb1value = $symb1->getValue();
        $symb2value = $symb2->getValue();
        $symb1type = $symb1->getType();
        $symb2type = $symb2->getType();
        if($symb1type != $symb2type)  //If types are not the same, throw exception
        {
            throw new TypeException();
        }
        //If types are bool, int or string, execute the operation else throw exception
        if($symb1type == DataTypeEnum::bool || $symb1type == DataTypeEnum::int || $symb1type == DataTypeEnum::string)
        {
            $variable->setValue($this->doExecute($symb1value, $symb2value));
            $variable->setType(DataTypeEnum::bool);
        }
        else
        {
            throw new TypeException();
        }
    }
    abstract public function doExecute(mixed $symb1value, mixed $symb2value) : bool;
}