<?php

namespace Catalog\Taobao;

use Catalog\Exception\ApiError;
use Catalog\ValueObject\Price;

class Variation
{
    private $data;
    private $price;
    private $id;
    private $properties;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->assignPrice();
        $this->assignQuantity();
        $this->assignId();
        $this->assignProperties();
    }

    private function assignPrice()
    {
        if (!isset($this->data['price'])) {
            throw new ApiError('cannot retrieve variation price');
        }
        $this->price = new Price($this->data['price'], 'CNY');
    }

    private function assignQuantity()
    {
        if (isset($this->data['quantity'])) {
            $this->quantity = (int) $this->data['quantity'];
        }
    }

    private function assignId()
    {
        $this->id = $this->data['sku_id'];
    }

    private function assignProperties()
    {
        if (!isset($this->data['properties_name'])) {
            throw new ApiError('no properties names, sorry :(');
        }
        $properties = explode(';', $this->data['properties_name']);
        foreach ($properties as $property) {
            list($pid, $vid, $name) = explode(':', $property);
            $this->properties[$pid . ':' . $vid] = ['pid' => $pid, 'vid' => $vid, 'name' => $name];
        }
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getProperties()
    {
        return $this->properties;
    }
}