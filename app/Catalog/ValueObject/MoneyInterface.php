<?php

namespace Catalog\ValueObject;

interface MoneyInterface
{
    public function getCurrency();
    public function getValue();
}