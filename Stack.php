<?php

namespace IPP\Student;

use IPP\Student\Exceptions\MissingValueException;
use IPP\Student\Operands\Literal;
use IPP\Student\Operands\Variable;

class Stack //singleton class to access the stack used in the instructions
{
    private static ?Stack $instance = null;
    /**
     * @var Literal[]
     */
    private array $stack;

    private function __construct()
    {
        $this->stack = [];
    }
    public static function getInstance() : Stack
    {
        if (self::$instance === null) {
            self::$instance = new Stack();
        }
        return self::$instance;
    }
    public function push(Literal $literal) : void
    {
        $this->stack[] = $literal;
    }

    /**
     * @throws MissingValueException
     */
    public function pop(Variable $variable) : void
    {
        if(empty($this->stack))
        {
            throw new MissingValueException;
        }
        $literal = array_pop($this->stack);
        $variable->setValue($literal->getValue());
        $variable->setType($literal->getType());
    }
}