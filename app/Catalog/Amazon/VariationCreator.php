<?php

namespace Catalog\Amazon;

use Catalog\ValueObject\Aspect;

class VariationCreator
{
    public function __construct($subitem)
    {
        $this->subitem = $subitem;
    }

    public function create()
    {
        $result = [];
        foreach ($this->subitem as $aspect) {
            $result[] = new Aspect($aspect['Name'], $aspect['Value']);
        }
        return $result;
    }
}