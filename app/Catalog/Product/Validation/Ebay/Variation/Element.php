<?php
namespace Catalog\Product\Validation\Ebay\Variation;

use Catalog\Exception\EmptyParam;
use Catalog\Product\VariationValue;

class Element
{
    public function fillingVariationValue($variationList, $imageList = array())
    {
        if (isset($variationList['Name'])) {
            return [$this->getVariationValue($variationList, $imageList)];
        }
        $valueList = array();
        foreach ((array)$variationList as $variationValue) {
            $valueList[] = $this->getVariationValue($variationValue, $imageList);
        }
        return $valueList;
    }

    private function getVariationValue(array $variationValue, $imageList)
    {
        $this->validationElement($variationValue);
        return new VariationValue(
            $variationValue['Name'], (array)$variationValue['Value'], $this->correctImage($variationValue, $imageList)
        );
    }

    private function correctImage($valueList = array(), $imageList = array())
    {
        $imageVariation = array();
        foreach ((array)$valueList['Value'] as $value) {
            if (!empty($imageList[$valueList['Name']][$value])) {
                $imageList = $imageList[$valueList['Name']][$value];
                $imageVariation = array_merge($imageVariation, $imageList);
            }
        }
        return $imageVariation;
    }

    private function validationElement($validation)
    {
        if (!isset($validation['Name'])) {
            throw new EmptyParam('Variation name is not exist');
        }
        if (!isset($validation['Value'])) {
            throw new EmptyParam('Variation value is not exist');
        }
    }
}