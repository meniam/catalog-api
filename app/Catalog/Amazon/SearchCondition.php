<?php

namespace Catalog\Amazon;

use Catalog\Exception\BadRequest;

class SearchCondition
{
    private $filters = ['ResponseGroup' => 'ItemIds'];

    public function setCategoryId($categoryId)
    {
        $this->filters['BrowseNode'] = $categoryId;
    }

    public function setCondition($condition)
    {
        $allowedCondition = ['Used', 'Collectible', 'Refurbished', 'All'];
        if (!in_array($condition, $allowedCondition)) {
            throw new BadRequest('invalid condition');
        }
        $this->filters['Condition'] = $allowedCondition;
    }

    public function setPage($page)
    {
        $this->filters['ItemPage'] = $page;
    }

    public function setMaxPrice($maxPrice)
    {
        $this->filters['MaximumPrice'] = round($maxPrice * 100);
    }

    public function setMinPrice($minPrice)
    {
        $this->filters['MinimumPrice'] = round($minPrice * 100);
    }

    public function sortByPrice()
    {
        $this->filters['Sort'] = 'price';
    }

    public function sortByPriceDesc()
    {
        $this->filters['Sort'] = '-price';
    }

    public function toArray()
    {
        return $this->filters;
    }
}