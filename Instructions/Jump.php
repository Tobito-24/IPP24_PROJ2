<?php

namespace IPP\Student\Instructions;

use IPP\Student\Exceptions\SemanticException;
use IPP\Student\ExecutionManager;
use IPP\Student\Operands\LabelManager;

class Jump implements InstructionInterface
{
    private string $labelName;
    public function __construct(string $labelName)
    {
        $this->labelName = $labelName;
    }

    /**
     * @throws SemanticException
     * @throws \Exception
     */
    public function execute() : void
    {
        $executionManager = ExecutionManager::getInstance();
        $labelManager = LabelManager::getInstance();
        $label = $labelManager->getOpOrder($this->labelName);
        $executionManager->setInstructionPointer($label); // set the instruction pointer to the label
    }
}