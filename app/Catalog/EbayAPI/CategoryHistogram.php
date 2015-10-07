<?php

namespace Catalog\EbayAPI;

class CategoryHistogram
{
    private $response;
    private $categories = [];

    public function __construct($response)
    {
        $this->response = $response;

        $this->assignCategories();
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function assignCategories()
    {
        if (isset($this->response['categoryHistogram']['categoryId'])) {
            $this->response['categoryHistogram'] = [$this->response['categoryHistogram']];
        }
        if (!isset($this->response['categoryHistogram'])) {
            return;
        }
        foreach ($this->response['categoryHistogram'] as $topCategory) {
            $this->categories[] = [
                'id' => $topCategory['categoryId'],
                'count' => $topCategory['count'],
                'subcats' => $this->fetchSubcategories($topCategory)
            ];
        }
    }

    private function fetchSubcategories($topCategory)
    {
        if (isset($topCategory['childCategoryHistogram']['categoryId'])) {
            $topCategory['childCategoryHistogram'] = [$topCategory['childCategoryHistogram']];
        }
        $result = [];
        if (!isset($topCategory['childCategoryHistogram'])) {
            return $result;
        }
        foreach ($topCategory['childCategoryHistogram'] as $cat) {
            $result[] = [
                'id' => $cat['categoryId'],
                'count' => $cat['count']
            ];
        }
        return $result;
    }
}