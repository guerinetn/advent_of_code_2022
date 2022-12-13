<?php

namespace App\Model;

class Tree
{
    public int $size;

    public ?int $topHeight;
    public ?int $bottomHeight;
    public ?int $leftHeight;
    public ?int $rightHeight;

    public function __construct(int $size)
    {
        $this->size = $size;
    }

}