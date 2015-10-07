<?php

namespace Catalog\Taobao;

use Catalog\Exception\ApiError;
use Catalog\ValueObject\Price;

class Product
{
    private $item;
    private $cid;
    private $expireAt;
    private $description;
    private $rating = 0;
    private $originalLink;
    private $shipping = [];
    private $condition = 'unknown';
    private $variations = [];

    public function __construct($data)
    {
        $this->item = $data['item'][0];
        $this->assignCategory();
        $this->assignExpireAt();
        $this->assignDescription();
        $this->assignRating();
        $this->assignOriginalLink();
        $this->assignShipping();
        $this->assignCondition();
        $this->assignCondition();
        $this->assignSkus();
    }

    private function assignCategory()
    {
        if (!isset($this->item['cid']) || !is_numeric($this->item['cid'])) {
            throw new ApiError('unknown cid');
        }
        $this->cid = $this->item['cid'];
    }

    private function assignExpireAt()
    {
        if (!isset($this->item['delist_time'])) {
            throw new ApiError('unknown expiration time');
        }
        if (!($date = date_parse($this->item['delist_time']))) {
            throw new ApiError('bad format of expiration time');
        }
        $this->expireAt = $date;
    }

    private function assignDescription()
    {
        if (isset($this->item['desc'])) {
            $this->description = $this->item['desc'];
        }
    }

    private function assignRating()
    {
        if (isset($this->item['auction_point'])) {
            $this->rating = $this->item['auction_point'];
        }
    }

    private function assignOriginalLink()
    {
        if (!isset($this->item['detail_url'])) {
            throw new ApiError('original link not found');
        }
        $this->originalLink = $this->item['detail_url'];
    }

    private function assignShipping()
    {
        if (isset($this->item['ems_fee'])) {
            $this->shipping['ems'] = new Price($this->item['ems_fee'], 'CNY');
        }
        if (isset($this->item['express_fee'])) {
            $this->shipping['express'] = new Price($this->item['express_fee'], 'CNY');
        }
        if (isset($this->item['post_fee'])) {
            $this->shipping['post'] = new Price($this->item['post_fee'], 'CNY');
        }
        if (isset($this->item['freight_payer'])) {
            $this->shipping['freight_payer'] = $this->item['freight_payer'];
        }
    }

    private function assignCondition()
    {
        if (!isset($this->item['stuff_status'])) {
            return;
        }
        switch ($this->item['stuff_status']) {
            case 'new':
                $this->condition = 1000;
                break;
            case 'unused':
                $this->condition = 1500;
                break;
            case 'second':
                $this->condition = 3000;
                break;
            default:
                throw new ApiError('incorrect condition');
        }
    }

    private function assignSkus()
    {
        if (!isset($this->item['skus']['sku'])) {
            return;
        }
        foreach ($this->item['skus']['sku'] as $variation) {
            $this->variations[] = new Variation($variation);
        }
    }

    public function getCategoryId()
    {
        return $this->cid;
    }

    public function getExpirationTime()
    {
        return $this->expireAt;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function getOriginalLink()
    {
        return $this->getOriginalLink();
    }

    public function getShipping()
    {
        return $this->shipping;
    }

    public function getCondition()
    {
        return $this->condition;
    }

    public function getVariations()
    {
        return $this->variations;
    }
}
