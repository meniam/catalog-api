<?php

namespace Catalog\EbayAPI;

class SearchCondition
{
    private $queryParams = [];
    private $filters = ['HideDuplicateItems' => true];
    private $aspects = [];

    public function setMaxPrice($maxPrice)
    {
        $this->filters['MaxPrice'] = $maxPrice;
    }

    public function setMinPrice($minPrice)
    {
        $this->filters['MinPrice'] = $minPrice;
    }

    public function fixedPriceOnly()
    {
        $this->filters['ListingType'] = 'FixedPrice';
    }

    public function auctionOnly()
    {
        $this->filters['ListingType'] = 'Auction';
    }

    public function setSeller($seller)
    {
        $this->filters['Seller'] = (array)$seller;
    }

    public function setCategoryId($id)
    {
        $this->queryParams['categoryId'] = $id;
    }

    public function setPage($page)
    {
        $this->queryParams['paginationInput.pageNumber'] = $page;
    }

    public function setItemsPerPage($num)
    {
        $this->queryParams['paginationInput.entriesPerPage'] = $num;
    }

    public function setProductCondition($cond)
    {
        $this->filters['Condition'] = $cond;
    }

    public function sortByPriceDesc()
    {
        $this->queryParams['sortOrder'] = 'PricePlusShippingHighest';
    }

    public function setSiteId($siteId)
    {
        $this->queryParams['X-EBAY-SOA-GLOBAL-ID'] = $siteId;
    }

    public function setKeywords($query)
    {
        $this->queryParams['keywords'] = $query;
    }

    public function sortOrderBestMatch()
    {
        $this->queryParams['sortOrder'] = 'BestMatch';
    }

    public function sortOrderPricePlusShippingHighest()
    {
        $this->queryParams['sortOrder'] = 'PricePlusShippingHighest';
    }

    public function sortOrderPricePricePlusShippingLowest()
    {
        $this->queryParams['sortOrder'] = 'PricePlusShippingLowest';
    }

    public function sortOrderStartTimeNewest()
    {
        $this->queryParams['sortOrder'] = 'StartTimeNewest';
    }

    public function sortOrderCurrentPriceHighest()
    {
        $this->queryParams['sortOrder'] = 'CurrentPriceHighest';
    }

    public function sortOrderStartTimeSoonest()
    {
        $this->queryParams['sortOrder'] = 'EndTimeSoonest';
    }

    public function setAspects($aspects)
    {
        $this->aspects = $aspects;
    }

    public function toArray()
    {
        return array_merge($this->queryParams, $this->filtersToQueryParams(), $this->buildAspectFilters());
    }

    private function filtersToQueryParams()
    {
        $params = [];
        $keys = array_keys($this->filters);
        for ($i = 0; $i < count($keys); $i++) {
            $name = $keys[$i];
            $params["itemFilter($i).name"] = $name;
            $values = $this->filters[$name];
            if (is_array($values)) {
                for ($j = 0; $j < count($values); $j++) {
                    $params["itemFilter($i).value($j)"] = $values[$j];
                }
            } else {
                $params["itemFilter($i).value"] = $values;
            }
        }
        return $params;
    }

    private function buildAspectFilters()
    {
        if (!$this->aspects) {
            return [];
        }
        $k = 0;
        $params = [];
        foreach ($this->aspects as $name=>$values) {
            $prefix = 'aspectFilter(' . $k . ').';
            $params[$prefix . 'aspectName'] = $name;
            $j = 0;
            if (is_array($values)) {
                foreach ($values as $value) {
                    $params[$prefix . 'aspectValueName(' . $j . ')'] = $value;
                    $j++;
                }
            } else {
                $params[$prefix . 'aspectValueName'] = $values;
            }
            $k++;
        }
        return $params;
    }
}