<?php

namespace Catalog\Taobao;

use Catalog\Exception\ApiError;

class ProductListItem
{
    private $data;
    private $title;
    private $picUrl;
    private $price;
    private $id;

    public function __construct($data)
    {
        $this->data = $data;
        $this->assignPrice();
        $this->title = $data['name'];
        $this->picUrl = $data['pic_path'];
        $this->price = $data['price'];
        $this->id = $data['item_id'];
    }

    public function assignPrice()
    {
        if (isset($this->data['price_with_rate'])) {
            $this->price = $this->data['price_with_rate'];
        } elseif (isset($this->data['price'])) {
            $this->price = $this->data['price'];
        } else {
            throw new ApiError('unknown price');
        }
    }

    public function getName()
    {
        return $this->title;
    }

    public function getPicUrl()
    {
        return $this->picUrl;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getId()
    {
        return $this->id;
    }
}
