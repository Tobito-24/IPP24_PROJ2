<?php

namespace IPP\Student\Instructions;

use Exception;
use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\MissingValueException;
use IPP\Student\Exceptions\NotExistException;
use IPP\Student\Exceptions\TypeException;
use IPP\Student\IO;
use IPP\Student\Operands\Literal;
use IPP\Student\Operands\Symbol;
use IPP\Student\Operands\Variable;

class Write implements InstructionInterface
{
    private string $symbName;
    private DataTypeEnum $symbType;

    private IO $io;
    public function __construct(string $symbName, DataTypeEnum $symbType, IO $io)
    {
        $this->symbName = $symbName;
        $this->symbType = $symbType;
        $this->io = $io;
    }

    /**
     * @throws FrameException
     * @throws NotExistException
     * @throws TypeException
     * @throws InvalidXMLStructure
     * @throws MissingValueException
     * @throws Exception
     */
    public function execute() : void
    {
        $symbol = Symbol::GetSymbol($this->symbType, $this->symbName);
        $type = $symbol->getType();
        $value = $symbol->getValue();
        switch($type):
            case DataTypeEnum::int:
            case DataTypeEnum::bool:
            case DataTypeEnum::string:
                if(is_int($value) || is_bool($value) || is_string($value))
                {
                    if($type == DataTypeEnum::string)
                    {
                        $value = preg_replace_callback('/\\\\(\d{3})/', function ($matches) {
                            return mb_convert_encoding(pack('n', $matches[1]), 'UTF-8', 'UCS-2BE'); // Get rid of escape sequences
                        }, (string)$value);
                        if($value === null)
                        {
                            throw new Exception;
                        }
                    }
                    $this->io->writeStdout($type, $value); // Write value
                }
                else
                {
                    throw new TypeException;
                }
                break;
            case DataTypeEnum::nil:
                $this->io->writeStdout(DataTypeEnum::string, ""); // Write empty string
                break;
            default:
                throw new TypeException;
        endswitch;
    }
}