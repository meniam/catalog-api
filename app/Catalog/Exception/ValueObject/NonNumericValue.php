<?php

namespace Catalog\Exception\ValueObject;

use Catalog\Exception\Basic;

class NonNumericValue extends Basic
{
    public function __construct($value)
    {
        $this->message = 'Value is not a number: ' . $value;
    }
}