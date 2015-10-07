<?php

namespace Catalog\Product;


class VariationValue
{
    private $name;

    private $valueList;

    private $imageList = array();

    public function __construct($name = null, $valueList = array(),  $imageList = array())
    {
        $this->name = $name;
        $this->valueList = $valueList;
        $this->imageList = $imageList;
    }

    /**
     * @return array
     */
    public function getImageList()
    {
        return $this->imageList;
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