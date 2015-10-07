<?php

namespace Catalog\EbayAPI;

/**
 * @property array $shippingCost
 * @property int $hitCountEbay
 * @property array $paymentMethodList
 * @property string $located
 * @property int $bidCount
 * @property string $siteId
 * @property array $errorsApi
 * @property bool $isAdult
 * @property float $minimumToBid
 * @property \Catalol\ValueObject\Price $buyItNow
 */
class Product extends \Catalog\Product
{
    public function __construct()
    {
        $this->allowedFields = array_merge($this->allowedFields, ['shippingCost', 'hitCountEbay', 'paymentMethodList',
            'located', 'bidCount', 'buyItNow', 'siteId', 'errorsApi', 'isAdult', 'minimumToBid', 'textDescription']);
    }
}