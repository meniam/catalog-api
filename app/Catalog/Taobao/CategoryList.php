<?php

namespace Catalog\Taobao;

use Catalog\Exception\ApiError;

class CategoryList
{
    private $categories = [];

    public function __construct($cats)
    {
        $requiredFields = ['cid' => 1, 'is_parent' => 1, 'name' => 1, 'parent_cid' => 1];
        if (!isset($cats['item_cats']['item_cat'])) {
            throw new ApiError;
        }
        if (isset($cats['item_cats']['item_cat']['cid'])) {
            $cats['item_cats']['item_cat'] = [$cats['item_cats']['item_cat']];
        }
        foreach ($cats['item_cats']['item_cat'] as $cat) {
            if (count(array_intersect_key($requiredFields, $cat)) < count($requiredFields)) {
                throw new ApiError;
            }
            $this->categories[] = $cat;
        }
    }

    public function getSubcategories()
    {
        return $this->categories;
    }
}
