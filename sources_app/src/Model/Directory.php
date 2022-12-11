<?php

namespace App\Model;

class Directory
{
    public string $name = '';
    public int $size = 0;

    public function __construct(string $name ='',$size=0)
    {
        $this->name = $name;
        $this->size = $size;
    }

    public function __toString(): string
    {
     return sprintf('%s (%s)',$this->name,$this->size);
    }
}