<?php

namespace Catalog;

class SkuNormalizer
{
    const MIN_SKU_LENGTH = 6;
    const MAX_SKU_LETTER_LENGTH = 10;

    public function isSku($str)
    {
        $rules = [
            !empty($str),
            !preg_match("#[^\$\sA-Za-z0-9_\-\.\,\/\\\s]#siu", $str),
            strlen($str) >= self::MIN_SKU_LENGTH,
            !(strlen($str) > self::MAX_SKU_LETTER_LENGTH && !preg_match("/\d/", $str))
        ];
        foreach ($rules as $rule) {
            if (!$rule) {
                return false;
            }
        }
        return true;
    }

    public function transform($str)
    {
        $combined = str_replace(['_', '-', '.', '/', '\\'], ['', '', '', ' ', ' '], strtoupper($str));
        $parts = explode(',', $combined);
        $parts = $this->removeEmptyElement($this->getTransformParts($parts));
        if (count($parts) > 1) {
           $parts =  array_map(function ($part) {return str_replace(' ', '', $part);}, $parts);
        } elseif (count($parts) == 1) {
            $parts = $this->removeEmptyElement($this->getTransformParts(explode(' ', reset($parts))));
        }
        $parts = $this->combineShortPart($parts);

        return array_values($this->validateParts($parts));
    }

    private function validateParts($parts)
    {
        foreach ($parts as $key => $part) {
            if (!$this->isSku($part)) {
                unset($parts[$key]);
            }
        }
        return $parts;
    }

    private function combineShortPart($parts)
    {
        if (empty($parts)) {
            return [];
        }
        $parts = array_values($parts);
        $minPart = $this->getMinElement($parts);
        if (count($parts) == 1 && strlen($minPart) <  self::MIN_SKU_LENGTH) {
            return [];
        }
        if (strlen($minPart) >= self::MIN_SKU_LENGTH) {
            return $parts;
        }
        $index = array_search($minPart, $parts);
        if ($index == (count($parts) - 1)) {
            $parts[$index - 1] = $parts[$index - 1] . $parts[$index];
        } else {
            $parts[$index + 1] = $parts[$index] . $parts[$index + 1];
        }
        unset($parts[$index]);
        return $this->combineShortPart($parts);
    }

    private function getMinElement($parts)
    {
        $minPart = null;
        foreach ($parts as $part) {
            if (strlen($part) < strlen($minPart) || is_null($minPart)) {
                $minPart = $part;
            }
        }
        return $minPart;
    }

    private function addPart($parts, $key, $value) {
        if (isset($parts[$key])) {
            $parts[$key] .= $value;
        } else {
            $parts[$key] = $value;
        }
        return $parts;
    }

    private function getTransformParts($parts)
    {
        $partsTransform = [];
        $i = 0;
        foreach ($parts as $part) {
            if (strlen($part) < self::MIN_SKU_LENGTH && $i > 0 && $i < (count($parts) - 1)) {
                $partsTransform = $this->addPart($partsTransform, $i - 1, $part);
                $i--;
            } elseif (strlen($part) < self::MIN_SKU_LENGTH && $i == (count($parts) - 1) && $i > 0) {
                $partsTransform = $this->addPart($partsTransform, array_search(current($partsTransform), $partsTransform),$part);
            } elseif (strlen($part) < self::MIN_SKU_LENGTH && $i == 0) {
                $partsTransform = $this->addPart($partsTransform, $i + 1, $part);
            } else {
                $partsTransform = $this->addPart($partsTransform, $i, $part);
            }
            $i++;
        }
        return $partsTransform;
    }

    private function removeEmptyElement($array)
    {
        return array_values(array_diff($array, ['']));
    }
}