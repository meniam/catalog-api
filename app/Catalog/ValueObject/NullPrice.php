<?php

namespace Catalog\ValueObject;

class NullPrice implements MoneyInterface
{
    /**
     * @return null
     */
    public function getCurrency()
    {
        return null;
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return null;
    }
}