<?php

namespace IPP\Student\Instructions;

interface InstructionInterface //common interface for all instructions
{
    public function execute() : void;
}