<?php

namespace Catalog\Amazon;

class DimensionsContainer
{
    const INCH = 0.0254; // meter
    const POUND = 0.45359237; // kg

    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getTransformedData()
    {
        $result = [];
        foreach ($this->data as $field=>$v) {
            $result[strtolower($field)] = $this->transform($v);
        }
        return $result;
    }

    private function transform($v)
    {
        $value = $v['#'];
        switch ($v['@Units']) {
            case 'hundredths-inches':
                return $value * self::INCH / 100;
            case 'hundredths-pounds':
                return $value * self::POUND / 100;
            case 'inches':
                return $value * self::POUND;
            default:
                throw new \Exception('Unknown units ' . $v['@Units']);
        }
    }
}