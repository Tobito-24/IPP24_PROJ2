<?php

namespace IPP\Student\Instructions;

use IPP\Student\Exceptions\SemanticException;
use IPP\Student\ExecutionManager;
use IPP\Student\Operands\LabelManager;

class Call implements InstructionInterface
{
    private string $labelName;
    private int $returnAddress;
    public function __construct(string $labelName, int $returnAddress)
    {
        $this->labelName = $labelName;
        $this->returnAddress = $returnAddress;
    }

    /**
     * @throws SemanticException
     * @throws \Exception
     */
    public function execute() : void
    {
        $executionManager = ExecutionManager::getInstance(); // get the instance of the execution manager
        $executionManager->pushReturn($this->returnAddress); // push the return address to the stack
        $labelManager = LabelManager::getInstance(); // get the instance of the label manager
        $label = $labelManager->getOpOrder($this->labelName); // get the order of the label
        $executionManager->setInstructionPointer($label); // set the instruction pointer to the order of the label
    }

}