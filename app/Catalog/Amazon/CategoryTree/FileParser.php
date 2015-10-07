<?php

namespace Catalog\Amazon\CategoryTree;

class FileParser
{
    private $lines;

    public function __construct($filename)
    {
        $this->lines = file($filename);
    }

    public function getCountries()
    {
        return array_slice(explode(';', $this->lines[0]), 1);
    }

    public function getCategories()
    {
        return array_map(function ($line) { return explode(';', $line)[0]; }, array_slice($this->lines, 1));
    }

    public function getTable()
    {
        $table = [];
        foreach ($this->getCountries() as $k => $country) {
            foreach (array_slice($this->lines, 1) as $line) {
                $items = explode(';', $line);
                $table[trim($country)][$items[0]] = trim($items[$k + 1]);
            }
        }
        return $table;
    }
}
