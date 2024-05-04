<?php

namespace IPP\Student\Instructions;

use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\MissingValueException;
use IPP\Student\Exceptions\NotExistException;
use IPP\Student\Operands\Variable;
use IPP\Student\Stack;

class Pops implements InstructionInterface
{
    private string $variable;
    public function __construct(string $variable)
    {
        $this->variable = $variable;
    }

    /**
     * @throws MissingValueException
     * @throws FrameException
     * @throws NotExistException
     * @throws InvalidXMLStructure
     */
    public function execute() : void
    {
        $stack = Stack::getInstance();
        $variable = Variable::GetVariable($this->variable);
        $stack->pop($variable); // Pops the top value from the stack and stores it in the variable
    }
}