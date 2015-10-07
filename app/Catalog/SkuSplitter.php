<?php

namespace Catalog;

class SkuSplitter
{
    private $str;

    public function __construct($str)
    {
        $this->str = $str;
    }

    public function getSkus()
    {
        if (preg_match('![^\w\-\,\;\/\\\\.#\s]!', $this->str)) {
            $variants = [new SkuSet([], '')];
        } elseif (preg_match('![;,]!', $this->str)) {
            $variants = [new SkuSet(preg_split('![;,]!', $this->str), '')];
        } elseif(preg_match_all('![\s\/\\\]!', $this->str, $matches)) {
            $delimiterChars = array_unique(str_split(implode('', $matches[0])));
            $variants = $this->getVariants($delimiterChars);
        } else {
            $variants = [new SkuSet([$this->str], '')];
        }
        return $this->getOptimalVariant($variants);
    }

    private function getVariants($delimiters)
    {
        $that = $this;
        return array_map(function ($char) use ($that) {
            return new SkuSet(explode($char, $that->str), $char);
        }, $delimiters);
    }

    private function getOptimalVariant($variants)
    {
        $maxCorrectness = 0;
        $result = [];
        foreach ($variants as $variant) {
            $currentCorrectness = $variant->getCorrectness();
            if ($currentCorrectness > $maxCorrectness ||
                ($currentCorrectness > 0 && $currentCorrectness == $maxCorrectness && count($variant->toArray()) > count($result))) {
                $maxCorrectness = $currentCorrectness;
                $result = $variant->toArray();
            }
        }
        return $result;
    }
}