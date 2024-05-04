<?php

namespace IPP\Student\Frames;

use IPP\Student\Enums\FrameTypeEnum;
use IPP\Student\Exceptions\SemanticException;
use IPP\Student\Operands\Variable;

class Frame
{

    /**
     * @var Variable[]
     */
    private array $variables;
    public function __construct()
    {
        $this->variables = []; // array of variables
    }

    public function checkDefVariable(string $name): bool
    {
        return array_key_exists($name, $this->variables); // check if variable exists
    }

    /**
     * @throws SemanticException
     */
    public function addVariable(Variable $var): void
    {
        if ($this->checkDefVariable($var->getName())) // check if variable exists
        {
            throw new SemanticException();
        }
        else
        {
            $this->variables[$var->getName()] = $var; // add variable
        }
    }

    public function getVariable(string $name): ?Variable
    {
        return $this->variables[$name];
    }
}