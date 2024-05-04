<?php

namespace IPP\Student\Operands;

use IPP\Student\Enums\DataTypeEnum;
use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\MissingValueException;
use IPP\Student\Exceptions\NotExistException;
use IPP\Student\Frames\Frame;
use IPP\Student\Frames\FrameManager;

class Variable
{
    private string $name;
    private mixed $value;
    private DataTypeEnum $type = DataTypeEnum::notSet;
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @throws MissingValueException
     */
    public function getValue(): mixed
    {
        if($this->type == DataTypeEnum::notSet)
        {
            throw new MissingValueException;
        }
        return $this->value;
    }


    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function getType() : DataTypeEnum
    {
        return $this->type;
    }
    public function setType(DataTypeEnum $type): void
    {
        $this->type = $type;
    }

    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @throws InvalidXMLStructure
     */
    public static function getFrame(string $name) : ?Frame //Returns frame by name of a variable
    {
        $frameManager = FrameManager::getInstance();
        $frameName = explode("@", $name)[0];
        $frame = null;
        if($frameName == "TF")
        {
            $frame = $frameManager->getTF();
        }
        else if($frameName == "LF")
        {
            $frame = $frameManager->getLF();
        }
        else if($frameName == "GF")
        {
            $frame = $frameManager->getGF();
        }
        else
        {
            throw new InvalidXMLStructure;
        }
        return $frame;
    }

    /**
     * @throws FrameException
     * @throws NotExistException|InvalidXMLStructure
     */
    public static function getVariable(string $name) : Variable //Returns variable by name
    {
        $frame = Variable::getFrame($name); //Get frame by name
        if($frame == null)
        {
            throw new FrameException();
        }
        $varName = explode("@", $name)[1];
        $variable = $frame->getVariable($varName); //Get variable by name from frame
        if($variable == null) //If variable does not exist throw exception
        {
            throw new NotExistException();
        }
        return $variable;
    }
}