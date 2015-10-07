<?php

namespace Catalog\Amazon;

/**
 * @property string $parentId
 * @property int $salesRank
 * @property array $features
 */
class Product extends \Catalog\Product
{
    public function __construct()
    {
        $this->allowedFields = array_merge($this->allowedFields, ['parentId', 'salesRank', 'features',
            'minPrice', 'maxPrice', 'packageDimensions', 'itemDimensions']);
    }

}
