<?php

namespace IPP\Student\Instructions;

use Exception;
use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\MissingValueException;
use IPP\Student\Exceptions\NotExistException;
use IPP\Student\Exceptions\TypeException;
use IPP\Student\Frames\FrameManager;
use IPP\Student\Operands\Literal;
use IPP\Student\Operands\Symbol;
use IPP\Student\Operands\Variable;

class Move extends VarSymbol
{

    /**
     * @throws FrameException
     * @throws MissingValueException
     * @throws NotExistException
     * @throws TypeException
     * @throws InvalidXMLStructure
     */
    public function execute() : void
    {
        $variable = Variable::getVariable($this->name);
        $symbol = Symbol::getSymbol($this->symbType, $this->symbolString);
        $value = $symbol->getValue();
        if($symbol->getType() === DataTypeEnum::string)
        {
            if(gettype($value) !== 'string')
            {
                throw new TypeException('Invalid type for string');
            }
            else
            {
                $value = preg_replace_callback('/\\\\(\d{3})/', function ($matches) { // Convert escape sequences
                    return mb_convert_encoding(pack('n', $matches[1]), 'UTF-8', 'UCS-2BE');
                }, (string)$value);
            }

        }
        $variable->setType($symbol->getType()); // Set the type of the variable
        $variable->setValue($value); // Set the value of the variable

    }
}