<?php

namespace IPP\Student\Operands;

class Label
{
    private string $name;
    private int $opOrder;

    public function __construct(string $name, int $opOrder)
    {
        $this->name = $name;
        $this->opOrder = $opOrder;
    }

    public function getName() : string
    {
        return $this->name;
    }
    public function getOpOrder() : int
    {
        return $this->opOrder;
    }
}