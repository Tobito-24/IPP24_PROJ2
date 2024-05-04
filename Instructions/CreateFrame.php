<?php

namespace IPP\Student\Instructions;

use IPP\Student\Frames\FrameManager;

class CreateFrame implements InstructionInterface
{
    private FrameManager $frameManager;
    public function __construct()
    {
        $this->frameManager = FrameManager::getInstance();
    }
    public function execute() : void
    {
        $this->frameManager->createFrame();
    }

}