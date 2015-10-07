<?php
namespace Catalog\Product;

class ShippingCostContainer
{
    private $type = null;

    private $servicePrice;

    private $listedServicePrice;

    private $name = null;


    /**
     * @return Price
     */
    public function getListedServicePrice()
    {
        return $this->listedServicePrice;
    }

    /**
     * @return Price
     */
    public function getServicePrice()
    {
        return $this->servicePrice;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setListedServicePrice($listedServicePrice)
    {
        $this->listedServicePrice = $listedServicePrice;
        return $this;
    }


    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setServicePrice($servicePrice)
    {
        $this->servicePrice = $servicePrice;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


}