<?php

namespace Catalog\Taobao;

class Histogram
{
    private $aspects;

    public function __construct($data)
    {
        if (!isset($data['item_props']['item_prop'])) {
            return;
        }
        foreach ($data['item_props']['item_prop'] as $aspect) {
            if (!isset($aspect['prop_values'])) {
                continue;
            }
            $values = [];
            foreach ($data['prop_values'] as $aspectValue) {
                $values[$aspectValue['vid']] = $aspectValue['name'];
            }
            $this->aspects[$aspect['pid']] = [
                'name' => $aspect['name'],
                'values' => $values
            ];
        }
    }

    public function getAspects()
    {
        return $this->aspects;
    }
}