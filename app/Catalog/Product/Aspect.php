<?php
namespace Catalog\Product;

class Aspect
{
    private $name;

    private $valueList;

    /**
     * @param $name
     * @param array $valueList
     */
    public function __construct($name, array $valueList)
    {
        $this->name =  $name;
        $this->valueList = $valueList;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getValueList()
    {
        return $this->valueList;
    }


}