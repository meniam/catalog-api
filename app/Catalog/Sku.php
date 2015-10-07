<?php

namespace Catalog;

class Sku
{
    const MIN_LENGTH = 6;
    const MAX_LETTERS = 10;

    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getNormalizedValue()
    {
        return preg_replace('![^A-Z\d]!', '', strtoupper($this->value));
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isCorrect()
    {
        $normalizedValue = $this->getNormalizedValue();
        if (empty($normalizedValue) || strlen($normalizedValue) < self::MIN_LENGTH) {
            return false;
        }
        if (preg_match('!^[A-Z]{' . self::MAX_LETTERS . ',}$!', $normalizedValue)) {
            return false;
        }
        if (!preg_match('!\d!', $normalizedValue)) {
            return false;
        }
        if (preg_match('!(^|\s)[A-Za-z]{4,}(\s|$)!us', $this->value)) {
            return false;
        }
        return true;
    }
}