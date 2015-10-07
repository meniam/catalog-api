<?php
namespace Catalog\Product;

class ShippingCost
{
    private $type = null;

    private $service;

    private $listedService;

    private $name = null;

    public function __construct(ShippingCostContainer $container)
    {
        $this->service = $container->getServicePrice();
        $this->listedService = $container->getListedServicePrice();
        $this->type = $container->getType();
        $this->name = $container->getName();
    }


    /**
     * @return Price
     */
    public function getListedService()
    {
        return $this->listedService;
    }

    /**
     * @return Price
     */
    public function getService()
    {
        return $this->service;
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




}