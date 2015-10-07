<?php

namespace Catalog;

class SkuSet
{
    private $originalSet;
    private $skuSet;
    private $char;

    public function __construct(array $skuSet, $char)
    {
        $this->originalSet = $skuSet;
        $this->skuSet = array_map(function ($x) { return new Sku($x); }, $skuSet);
        $this->char = $char;
        $this->autocorrect();
    }

    public function autocorrect()
    {
        if ($this->originalSet && $this->getCorrectness() < 0.99) {
            $this->skuSet = [new Sku(implode($this->char, $this->originalSet))];
        }
    }

    public function toArray()
    {
        return $this->skuSet;
    }

    public function getCorrectness()
    {
        if (!$this->skuSet) {
            return 0;
        }
        $correctCount = count(array_filter($this->skuSet, function ($sku) { return $sku->isCorrect(); }));
        return $correctCount / count($this->skuSet);
    }


}