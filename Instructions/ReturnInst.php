<?php

namespace IPP\Student\Instructions;

use IPP\Student\Exceptions\MissingValueException;
use IPP\Student\ExecutionManager;
use IPP\Student\Operands\LabelManager;

class ReturnInst implements InstructionInterface
{
    public function __construct()
    {
    }

    /**
     * @throws \Exception
     */
    public function execute() : void
    {
        $executionManager = ExecutionManager::getInstance();
        $returnAdress = $executionManager->popReturn(); // get the return address from the stack
        if($returnAdress == null)
        {
            throw new MissingValueException;
        }
        $executionManager->setInstructionPointer($returnAdress); // set the instruction pointer to the return address
    }
}