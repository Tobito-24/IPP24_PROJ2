<?php

namespace IPP\Student\Instructions;

use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\SemanticException;
use IPP\Student\Frames\Frame;
use IPP\Student\Frames\FrameManager;
use IPP\Student\Operands\Variable;

class Defvar implements InstructionInterface
{
    private string $name;
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @throws FrameException
     * @throws InvalidXMLStructure
     * @throws SemanticException
     */
    public function execute() : void
    {
        $frame = Variable::getFrame($this->name); // get frame from variable name
        $varName = explode("@", $this->name)[1]; // get variable name
        $variable = new Variable($varName); // create new variable
        if($frame == null) // if frame does not exist throw exception
        {
            throw new FrameException();
        }
        $frame->addVariable($variable); // add variable to frame
    }
}