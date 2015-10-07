<?php

namespace Catalog\Exception\ValueObject;

use Catalog\Exception\Basic;

class NegativePrice extends Basic
{
    public function __construct($value)
    {
        $this->message = 'Price cannot be negative number: ' . $value;
    }
}