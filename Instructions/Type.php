<?php

namespace IPP\Student\Instructions;

use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\NotExistException;
use IPP\Student\Exceptions\TypeException;
use IPP\Student\Operands\Literal;
use IPP\Student\Operands\Symbol;
use IPP\Student\Operands\Variable;

class Type extends VarSymbol
{
    /**
     * @throws TypeException
     * @throws NotExistException
     * @throws FrameException
     * @throws InvalidXMLStructure
     */
    public function execute() : void
    {
        $variable = Variable::GetVariable($this->name);
        $symbol = Symbol::GetSymbol($this->symbType, $this->symbolString);
        $type = $symbol->getType();
        if($type == DataTypeEnum::nil)
        {
            $string = "nil";
        }
        else if ($type == DataTypeEnum::int)
        {
            $string = "int";
        }
        else if ($type == DataTypeEnum::bool)
        {
            $string = "bool";
        }
        else if ($type == DataTypeEnum::string)
        {
            $string = "string";
        }
        else if($type == DataTypeEnum::notSet)
        {
            $string = "";
        }
        else
        {
            throw new TypeException;
        }
        $variable->setValue($string);
        $variable->setType(DataTypeEnum::string);
    }
}