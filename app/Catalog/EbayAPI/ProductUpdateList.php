<?php

namespace Catalog\EbayAPI;

use Catalog\EbayItem\Single;
use Catalog\Exception\ApiError;

class ProductUpdateList
{
    private $products = [];
    private $errors = [];

    public function __construct($response)
    {
        $this->products = $response['Item'];
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