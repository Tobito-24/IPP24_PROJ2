<?php

namespace IPP\Student\Operands;

use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\MissingValueException;
use IPP\Student\Exceptions\NotExistException;

class Symbol
{

    /**
     * @param DataTypeEnum $symbolType
     * @param string $symbolString
     * @return Variable|Literal
     * @throws FrameException
     * @throws InvalidXMLStructure
     * @throws NotExistException
     */
    public static function getSymbol(DataTypeEnum $symbolType, string $symbolString) : Variable|Literal //Returns the symbol given by the type and name
    {
        if($symbolType == DataTypeEnum::var)
        {
            $symbol = Variable::GetVariable($symbolString);
        }
        else
        {
            $symbol = Literal::GetLiteral($symbolType, $symbolString);
        }
        return $symbol;
    }

    /**
     * @throws InvalidXMLStructure
     */
    public static function getTypeFromStr(string $type) : DataTypeEnum //Returns the type of the symbol
    {
        switch($type){
            case "int":
                return DataTypeEnum::int;
            case "bool":
                return DataTypeEnum::bool;
            case "string":
                return DataTypeEnum::string;
            case "nil":
                return DataTypeEnum::nil;
            case "var":
                return DataTypeEnum::var;
            default:
                throw  new InvalidXMLStructure;
        }
    }
}