<?php
namespace Catalog;

abstract class AbstractProduct
{
    protected $condition;

    protected $description;

    protected $expireAt;

    protected $imageList = array();

    protected $isAuction = false;

    protected $seller;

    protected $name;

    protected $variation = array();

    protected $aspect = array();

    protected $originalId;

    protected $originalLink;

    protected $categoryId;

    protected $shippingCostSummary;

    protected $price;

    protected $currency;

    protected $amount;

    protected $hitCountEbay;

    protected $paymentMethodList;

    protected $located;

    protected $country;

    protected $bidCount;

    protected $buyItNow;

    protected $siteId;

    protected $errorsApi = array();

    protected $isAdult;

    protected $minimumToBid;

    /**
     * @return \Catalog\Product\Price
     */
    public function getMinimumToBid()
    {
        return $this->minimumToBid;
    }


    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }


    /**
     * @return \Catalog\Product\Aspect[]
     */
    public function getAspectList()
    {
        return $this->aspect;
    }

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @return mixed
     */
    public function getSiteId()
    {
        return $this->siteId;
    }


    /**
     * @return \Catalog\Product\Condition
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @return string
     */
    public function getConditionName()
    {
        return $this->getCondition()->getName();
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return \DateTime
     */
    public function getExpirationTime()
    {
        return $this->expireAt;
    }

    /**
     * @return boolean
     */
    public function isAdult()
    {
        return $this->isAdult;
    }


    /**
     * @return array
     */
    public function getImageList()
    {
        return $this->imageList;
    }

    /**
     * @return boolean
     */
    public function isAuction()
    {
        return $this->isAuction;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (string)$this->name;
    }
    /**
     * @return string
     */
    public function getOriginalId()
    {
        return $this->originalId;
    }

    /**
     * @return string
     */
    public function getOriginalLink()
    {
        return $this->originalLink;
    }

    /**
     * @return \Catalog\Product\Seller
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * @return string
     */
    public function getSellerName()
    {
        return (string)$this->getSeller()->getName();
    }

    /**
     * @return \Catalog\Product\ShippingCost[]
     */
    public function getShippingCostSummary()
    {
        return $this->shippingCostSummary;
    }

    /**
     * @return \Catalog\Product\Variation[]
     */
    public function getVariationList()
    {
        return $this->variation;
    }

    /**
     * @return int
     */
    public function getHitCountEbay()
    {
        return $this->hitCountEbay;
    }

    /**
     * @return array
     */
    public function getPaymentMethodList()
    {
        return $this->paymentMethodList;
    }

    /**
     * @return string
     */
    public function getLocated()
    {
        return $this->located;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return int
     */
    public function getBidCount()
    {
        return $this->bidCount;
    }

    /**
     * @return \Catalog\Product\Price
     */
    public function getBuyItNow()
    {
        return $this->buyItNow;
    }

    /**
     * @return array
     */
    public function getErrorsApi()
    {
        return $this->errorsApi;
    }
}