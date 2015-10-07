<?php

namespace Catalog\Taobao;

class ProductList
{
    private $items = [];
    private $total;

    public function __construct($data)
    {
        foreach ($data['item_list']['tmall_extend_search_item'] as $product) {
            $this->items[] = new ProductListItem($product);
        }
        $this->total = (int) $data['total_results'];
    }

    public function getItems()
    {
        return $this->items;
    }

    public function totalCount()
    {
        return $this->total;
    }
}