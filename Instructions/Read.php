<?php

namespace IPP\Student\Instructions;

use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\NotExistException;
use IPP\Student\IO;
use IPP\Student\Operands\Variable;

class Read implements InstructionInterface
{
    private string $variable;
    private DataTypeEnum $type;

    private IO $io;
    public function __construct(string $var, DataTypeEnum $type, IO $io)
    {
        $this->variable = $var;
        $this->type = $type;
        $this->io = $io;
    }

    /**
     * @throws FrameException
     * @throws InvalidXMLStructure
     * @throws NotExistException
     */
    public function execute() : void
    {
        $var = Variable::getVariable($this->variable);
        $value = $this->io->read($this->type);
        if($value == null) // setting nil if no value is read
        {
            $var->setValue("nil");
            $var->setType(DataTypeEnum::nil);
        }
        else
        {
            $var->setValue($value);
            $var->setType($this->type);
        }
    }
}