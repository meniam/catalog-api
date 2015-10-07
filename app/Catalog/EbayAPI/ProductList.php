<?php

namespace Catalog\EbayAPI;

use Catalog\EbayItem\Single;
use Catalog\Exception\ApiError;

class ProductList
{
    private $products = [];
    private $errors = [];

    public function __construct($productsWithDescription, $productsWithTextDescription)
    {
        if (count($productsWithDescription['Item']) != count($productsWithTextDescription['Item'])) {
            throw new ApiError('product count mismatch');
        }
        for ($i = 0; $i < count($productsWithDescription['Item']); $i++) {
            $product = $productsWithDescription['Item'][$i];
            $product['TextDescription'] = $productsWithTextDescription['Item'][$i]['Description'];
            $this->products[] = $product;
        }
    }

    public function toObjects()
    {
        $result = [];
        foreach ($this->products as $product) {
            try {
                $result[] = (new Single($product))->parse();
            } catch (\Exception $e) {
                $this->errors[] = $e;
            }
        }
        return $result;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}