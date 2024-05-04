<?php

namespace IPP\Student\Instructions;

use IPP\Student\Exceptions\FrameException;
use IPP\Student\Frames\FrameManager;

class PushFrame implements InstructionInterface
{
    private FrameManager $frameManager;
    public function __construct()
    {
        $this->frameManager = FrameManager::getInstance();
    }

    /**
     * @throws FrameException
     */
    public function execute() : void
    {
        $this->frameManager->pushFrame();
    }

}