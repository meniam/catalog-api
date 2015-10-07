<?php

namespace Catalog\Exception\ValueObject;

use Catalog\Exception\Basic;

class UnknownCurrencyCode extends Basic
{
    public function __construct($currency)
    {
        $this->message = 'Unknown currency code: ' . $currency;
    }
}