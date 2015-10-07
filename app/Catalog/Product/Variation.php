<?php

namespace Catalog\Product;


use Catalog\ValueObject\MoneyInterface;

class Variation
{
    private $amount;

    private $valueList = array();

    private $price;

    public function __construct(MoneyInterface $price, $amount,  $valueList = array())
    {
        $this->price = $price;
        $this->amount =$amount;
        $this->valueList = (array)$valueList;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return Price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return \Catalog\Product\VariationValue[]
     */
    public function getValueList()
    {
        return $this->valueList;
    }



}