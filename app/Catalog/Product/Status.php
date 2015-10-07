<?php
namespace Catalog\Product;

use Catalog\ValueObject\MoneyInterface;

class Status
{
    private $price;

    private $bidCount;

    public function __construct(MoneyInterface $price, $bidCount, $expireAt)
    {
        $this->bidCount = $bidCount;
        $this->price = $price;
        $this->expireAt =  new \DateTime($expireAt);
    }

    /**
     * @return \DateTime
     */
    public function getExpireAt()
    {
        return $this->expireAt;
    }

    /**
     * @return int
     */
    public function getBidCount()
    {
        return $this->bidCount;
    }

    /**
     * @return Price
     */
    public function getPrice()
    {
        return $this->price;
    }


}