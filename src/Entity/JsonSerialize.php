<?php

namespace App\Entity;

trait JsonSerialize
{
    public function jsonSerialize(): mixed
    {
        $vars = get_object_vars($this);
        
        return $vars;
    }
}