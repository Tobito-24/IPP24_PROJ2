<?php

namespace IPP\Student\Operands;

use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\MissingValueException;

class Literal
{
    private DataTypeEnum $type;
    private mixed $value;

    public function __construct()
    {
    }

     public function getType(): DataTypeEnum
    {
        return $this->type;
    }

    public function getValue() : mixed
    {
        return $this->value;
    }
    //A function that takes a symbol and returns a literal (for stack purposes)

    /**
     * @throws MissingValueException
     */
    public static function getLiteralFromSymbol(Variable|Literal $symbol) : Literal
    {
        $literal = new Literal();
        $literal->type = $symbol->getType();
        $literal->value = $symbol->getValue();
        return $literal;
    }

    /**
     * @throws InvalidXMLStructure
     */
    //A function that takes a data type and a value and returns a literal
    public static function getLiteral(DataTypeEnum $type, string $value): Literal
    {
        $literal = new Literal();
        $literal->type = $type;
        switch($type):
            case DataTypeEnum::int:
                if(preg_match("/^-?\d+$/", $value) || preg_match("/^-?0x[0-9a-fA-F]+$/", $value)) //Check if the value is an integer
                {
                    $literal->value = intval($value);
                }
                else
                {
                    throw new InvalidXMLStructure;
                }
                break;
            case DataTypeEnum::string:
                $value = preg_replace_callback('/\\\\(\d{3})/', function ($matches) {
                    return mb_convert_encoding(pack('n', $matches[1]), 'UTF-8', 'UCS-2BE'); //Convert the unicode to UTF-8
                }, (string)$value);
                $literal->value = $value;
                break;
            case DataTypeEnum::bool:
                $literal->value = $value ==="true";
                break;
            case DataTypeEnum::nil:
                $literal->value = "nil";
                break;
            default:
                break;
        endswitch;
        return $literal;
    }
}