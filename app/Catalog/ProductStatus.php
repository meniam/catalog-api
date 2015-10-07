<?php
namespace Catalog;

use Catalog\Exception\EmptyParam;
use Catalog\ValueObject\Price;
use Catalog\Product\Status;
use foo\bar\Exception;

class ProductStatus
{
    /**
     * @param $response
     * @return Status
     */
    public function getStatus($response)
    {
        $this->validate($response);
        $price = new Price($response['ConvertedCurrentPrice']['#'], $response['ConvertedCurrentPrice']['@currencyID']);
        return new Status($price, $response['BidCount'], $response['EndTime']);
    }

    private function validate($item)
    {
        if (!isset($item['BidCount'])) {
            throw new EmptyParam('bid count not exists');
        }
        if (empty($item['ConvertedCurrentPrice']['#'])) {
            throw new EmptyParam('Item price not found');
        }
        if (empty($item['ConvertedCurrentPrice']['@currencyID'])) {
            throw new EmptyParam('Item currency not found');
        }
        if (empty($item['EndTime'])) {
            throw new EmptyParam('EndTime not found');
        }
    }
}