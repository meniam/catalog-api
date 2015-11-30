<?php
namespace Catalog;

use Catalog\Exception\EmptyParam;
use Catalog\Product\ProductShortInfo;

class ItemList
{
    private $result;

    private $count;

    public function __construct($response, $count)
    {
        $this->assignResult($response);
        $this->count = $count;
    }

    private function assignResult($response)
    {
        if (!isset($response['item'])) {
            return;
        }

        if ($response['@count'] == 1) {
            $response['item'] = [$response['item']];
        }

        foreach ($response['item'] as $item) {
            $this->validation($item);
            $this->result[] = new ProductShortInfo($item);
        }
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return \Catalog\Product\ProductShortInfo[]
     */
    public function getResult()
    {
        return $this->result;
    }

    private function validation($item)
    {
        if (empty($item['itemId'])) {
            throw new EmptyParam('Item id not found');
        }
        if (empty($item['sellingStatus']['currentPrice']['#'])) {
            throw new EmptyParam('Item price not found');
        }
        if (empty($item['sellingStatus']['currentPrice']['@currencyId'])) {
            throw new EmptyParam('Item currency not found');
        }
    }


}