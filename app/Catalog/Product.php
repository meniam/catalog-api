<?php

namespace Catalog;

/**
 * Class Product
 * @property Condition $condition
 * @property string $description
 * @property string $expireAt
 * @property string defaultImage
 * @property array $imageList
 * @property Seller $seller
 * @property string $name
 * @property array $aspects
 * @property array $variations
 * @property string $originalId
 * @property string $originalLink
 * @property string $categoryId
 * @property \Catalol\ValueObject\Price $price
 * @property int $amount
 * @property string $country
 * @property bool $isAuction
 */
class Product extends Freezable
{
    protected $allowedFields = ['condition', 'description', 'expireAt', 'defaultImage', 'imageList', 'seller', 'name',
        'aspects', 'variations', 'originalId', 'originalLink', 'categoryId', 'price', 'amount', 'country',
        'isAuction'];
}