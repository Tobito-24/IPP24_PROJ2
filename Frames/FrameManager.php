<?php

namespace IPP\Student\Frames;

use IPP\Student\Exceptions\FrameException;

class FrameManager //Singleton class for handling of frames
{
    private static ?FrameManager $instance = null;

    /**
     * @var Frame[]
     */
    private array $frameStack;
    private ?Frame $TF = null;
    private ?Frame $LF = null;
    private Frame $GF;

    private function __construct()
    {
        $this->GF = new Frame();
        $this->frameStack = [];
    }

    public static function getInstance(): FrameManager //Singleton pattern
    {
        if (self::$instance === null) {
            self::$instance = new FrameManager();
        }

        return self::$instance;
    }

    /**
     * @throws FrameException
     */
    public function pushFrame(): void //Pushes current frame to the stack
    {
        if($this->TF == null)
        {
            throw new FrameException();
        }
        $this->frameStack[] = $this->TF;
        $this->LF = $this->TF;
        $this->TF = null;
    }

    /**
     * @throws FrameException
     */
    public function popFrame(): ?Frame //Pops frame from the stack
    {
        $this->TF = array_pop($this->frameStack);
        if($this->TF === null) {
            throw new FrameException();
        }
        else
        {
            $frame = end($this->frameStack);
            if(!$frame)
            {
                $this->LF = null;
            }
            else
            {
                $this->LF = $frame;
            }
            return $this->TF;
        }
    }

    public function CreateFrame(): Frame //Creates new frame
    {
        $this->TF = new Frame();
        return $this->TF;
    }
    public function getTF(): ?Frame
    {
        return $this->TF;
    }
    public function getLF(): ?Frame
    {
        return $this->LF;
    }

    public function getGF(): Frame
    {
        return $this->GF;
    }
}