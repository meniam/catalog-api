<?php

namespace Catalog\Amazon;

class ProductList
{
    private $totalCount;
    private $ids;
    private $totalPages;

    public function __construct($response)
    {
        $this->totalCount = $response['Items']['TotalResults'];
        $this->totalPages = $response['Items']['TotalPages'];
        $this->ids = array_map(function ($x) {
            if (isset($x['ParentASIN'])) {
                return $x['ParentASIN'];
            }
            return $x['ASIN'];
        }, $response['Items']['Item']);
    }

    public function getTotalCount()
    {
        return $this->totalCount;
    }

    public function getTotalPages()
    {
        return $this->totalPages;
    }

    public function getIds()
    {
        return $this->ids;
    }
}