<?php

namespace App\Model;

/** Magic method for comparison don't exist with PHP.... */
class Tree
{
    public int $size;

    public ?bool $vR;
    public ?bool $vT;
    public ?bool $vL;
    public ?bool $vB;

    public function __construct(int $size)
    {
        $this->size = $size;
    }

}