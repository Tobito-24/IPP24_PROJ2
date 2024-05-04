<?php

namespace IPP\Student\Instructions;

use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Exceptions\SemanticException;
use IPP\Student\Exceptions\TypeException;
use IPP\Student\ExecutionManager;
use IPP\Student\Operands\LabelManager;
use IPP\Student\Operands\Symbol;

class JumpIfNeq implements InstructionInterface
{
    private string $labelName;
    private string $symbolString1;
    private string $symbolString2;
    private DataTypeEnum $symbolType1;
    private DataTypeEnum $symbolType2;
    public function __construct(string $labelName, string $symbolString1, string $symbolString2, DataTypeEnum $symbolType1, DataTypeEnum $symbolType2)
    {
        $this->labelName = $labelName;
        $this->symbolString1 = $symbolString1;
        $this->symbolString2 = $symbolString2;
        $this->symbolType1 = $symbolType1;
        $this->symbolType2 = $symbolType2;
    }

    /**
     * @throws SemanticException
     * @throws TypeException
     * @throws \Exception
     */
    public function execute(): void
    {
        $executionManager = ExecutionManager::getInstance();
        $labelManager = LabelManager::getInstance();
        $label = $labelManager->getOpOrder($this->labelName);
        $symbol1 = Symbol::getSymbol($this->symbolType1, $this->symbolString1);
        $symbol2 = Symbol::getSymbol($this->symbolType2, $this->symbolString2);
        $symb1value = $symbol1->getValue();
        $symb2value = $symbol2->getValue();
        $symb1type = $symbol1->getType();
        $symb2type = $symbol2->getType();
        if($symb1type != $symb2type && ($symb1type != DataTypeEnum::nil && $symb2type != DataTypeEnum::nil))
        {
            throw new TypeException();
        }
        if($symb1type == DataTypeEnum::nil || $symb2type == DataTypeEnum::nil)
        {
            if($symb1type == $symb2type)
            {
                return;
            }
            else
            {
                $executionManager->setInstructionPointer($label);
            }
        }
        else if($symb1type == DataTypeEnum::bool || $symb1type == DataTypeEnum::int || $symb1type == DataTypeEnum::string)
        {
            if($symb1type == $symb2type)
            {
                $jump = $symb1value != $symb2value;
                if($jump)
                {
                    $executionManager->setInstructionPointer($label);
                }
            }
            else
            {
                throw new TypeException();
            }
        }
        else
        {
            throw new TypeException();
        }
    }
}