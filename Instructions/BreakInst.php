<?php

namespace IPP\Student\Instructions;

use IPP\Student\IO;

class BreakInst implements InstructionInterface //not fully implemented instruction for Break.
{
    private IO $IO;
    public function __construct(IO $IO)
    {
        $this->IO = $IO;
    }
    public function execute(): void
    {
        $this->IO->writeStderr("Everything is fine.");
    }
}