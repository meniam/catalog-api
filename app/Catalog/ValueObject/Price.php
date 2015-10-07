<?php
namespace Catalog\ValueObject;

use Catalog\Exception\ValueObject\NegativePrice;

class Price extends Money
{
    public function __construct($value, $currency)
    {
        parent::__construct($value, $currency);
        if ($value < 0) {
            throw new NegativePrice($value);
        }
    }
}