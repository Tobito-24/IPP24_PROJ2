<?php

namespace IPP\Student\Instructions;

use IPP\Student\Exceptions\SemanticException;
use IPP\Student\Operands\Label;
use IPP\Student\Operands\LabelManager;

class LabelInst implements InstructionInterface
{
    private string $label;
    private int $opOrder;
    public function __construct(string $label, int $opOrder)
    {
        $this->label = $label;
        $this->opOrder = $opOrder;
    }

    /**
     * @throws SemanticException
     */
    public function do():void // add label to label manager before executing other instructions so that we can jump to the label ahead
    {
        $labelManager = LabelManager::getInstance();
        $label = new Label($this->label, $this->opOrder);
        $labelManager->addLabel($label);
    }
    public function execute():void
    {
    }
}