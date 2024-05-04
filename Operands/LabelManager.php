<?php

namespace IPP\Student\Operands;

use IPP\Student\Exceptions\SemanticException;

class LabelManager // singleton class to manage labels and make them accessible to all instructions
{
    private static ?LabelManager $instance = null;
    /**
     * @var Label[]
     */
    private array $labels;

    private function __construct()
    {
        $this->labels = [];
    }
    public static function getInstance() : LabelManager
    {
        if (self::$instance === null) {
            self::$instance = new LabelManager();
        }
        return self::$instance;
    }

    /**
     * @throws SemanticException
     */
    public function addLabel(Label $label) : void
    {
        if(isset($this->labels[$label->getName()])) // if label already exists throw an exception
        {
            throw new SemanticException;
        }
        $this->labels[$label->getName()] = $label; // add label to the array
    }
    public function getOpOrder(string $name) : int
    {
        if($this->labels[$name] == null) //if the label doesn't exist throw an exception
        {
            throw new SemanticException;
        }
        return $this->labels[$name]->getOpOrder(); // return the order of the label for jumping purposes
    }
}