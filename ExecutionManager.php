<?php

namespace IPP\Student;

use IPP\Student\Instructions\InstructionInterface;

class ExecutionManager // Singleton class to manage the execution of the instructions
{
    private static ?ExecutionManager $instance = null;
    private int $instructionPointer;
    /** @var InstructionInterface[] $instructions */
    private array  $instructions;

    /** @var int[] $returns */
    private array $returns;
    /**
     * ExecutionManager constructor.
     * @param InstructionInterface[] $instructions
     */
    private function __construct(array $instructions)
    {
        $this->instructions = $instructions;
        $this->instructionPointer = 1;
        $this->returns = [];
    }

    /**
     * @param InstructionInterface[] $instructions
     * @return ExecutionManager
     */
    public static function setInstance(array $instructions): ExecutionManager
    {
        if (self::$instance === null) {
            self::$instance = new ExecutionManager($instructions);
        }
        return self::$instance;
    }

    /**
     * @throws \Exception
     */
    public static function getInstance(): ExecutionManager
    {
        if (self::$instance === null) {
            throw new \Exception('ExecutionManager not set');
        }
        return self::$instance;
    }
    public function execute(): void //loop that executes the instructions with the instruction pointer changeable by the instructions
    {
        $maxKey = max(array_keys($this->instructions));
        while ($this->instructionPointer <= $maxKey) {
            if(array_key_exists($this->instructionPointer, $this->instructions)){
                $inst = $this->instructions[$this->instructionPointer];
                $inst->execute();
                $this->instructionPointer++;
            }
            else
            {
                $this->instructionPointer++;
                continue;
            }
        }
    }

    public function getInstructionPointer(): int
    {
        return $this->instructionPointer;
    }

    public function setInstructionPointer(int $instructionPointer): void
    {
        $this->instructionPointer = $instructionPointer;
    }

    public function pushReturn(int $return): void
    {
        $this->returns[] = $return;
    }

    public function popReturn(): ?int
    {
        return array_pop($this->returns);
    }

}