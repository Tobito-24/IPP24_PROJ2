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

abstract class ArithmeticOp extends VariableTwoSymbols //abstract class for arithmetic operations
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
        $variable = Variable::getVariable($this->var); //get variable
        $symb1 = Symbol::GetSymbol( $this->symbType1,$this->symb1); //get first symbol
        $symb2 = Symbol::GetSymbol( $this->symbType2,$this->symb2); //get second symbol
        $symb1value = $symb1->getValue();
        $symb2value = $symb2->getValue();
        if(gettype($symb1value) != 'integer' || gettype($symb2value) != 'integer') //check if both symbols are integers
        {
            throw new TypeException();
        }
        $value = $this->doExecute($symb1value, $symb2value);
        $variable->setType(DataTypeEnum::int); //set variable type to integer
        $variable->setValue($value);
    }
    abstract public function doExecute(int $symb1, int $symb2) : int;
}