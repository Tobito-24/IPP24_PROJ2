<?php

namespace IPP\Student\Instructions;

use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\MissingValueException;
use IPP\Student\Exceptions\NotExistException;
use IPP\Student\Exceptions\TypeException;
use IPP\Student\Operands\Literal;
use IPP\Student\Operands\Variable;

abstract class LogicalOp implements InstructionInterface //abstract class for logical operations
{
    private string $var;
    private string $symb1;
    private string $symb2;
    private DataTypeEnum $symbType1;
    private DataTypeEnum $symbType2;

    public function __construct(string $var, string $symb1, DataTypeEnum $symbType1, string $symb2,DataTypeEnum $symbType2)
    {
        $this->var = $var;
        $this->symb1 = $symb1;
        $this->symb2 = $symb2;
        $this->symbType1 = $symbType1;
        $this->symbType2 = $symbType2;
    }

    /**
     * @throws MissingValueException
     * @throws NotExistException
     * @throws TypeException
     * @throws FrameException
     * @throws InvalidXMLStructure
     */
    public function execute() : void
    {
        $variable = Variable::getVariable($this->var);
        if ($this->symbType1 == DataTypeEnum::var) {
            $symb1 = Variable::getVariable($this->symb1);
        } else {
            $symb1 = Literal::GetLiteral($this->symbType1, $this->symb1);
        }
        if ($this->symbType2 == DataTypeEnum::var) {
            $symb2 = Variable::getVariable($this->symb2);
        } else {
            $symb2 = Literal::GetLiteral($this->symbType2, $this->symb2);
        }
        $symb1value = $symb1->getValue();
        $symb2value = $symb2->getValue();
        if($symb1->getType() != DataTypeEnum::bool || $symb2->getType() != DataTypeEnum::bool) {
            throw new TypeException();
        }
        if(gettype($symb1value) != 'boolean' || gettype($symb2value) != 'boolean') { //checking if the type of the operands is boolean
            throw new TypeException();
        }
        $variable->setValue($this->doExecute($symb1value, $symb2value)); //executing the logical operation
        $variable->setType(DataTypeEnum::bool); //setting the type of the variable to boolean
    }
    abstract public function doExecute(bool $symb1value, bool $symb2value) : bool; //abstract method for executing logical operations
}