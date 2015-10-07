<?php

namespace Catalog\ValueObject;

class Aspect
{
    private $name;
    private $values;

    public function __construct($name, $values)
    {
        $this->name = $name;
        $this->values = $values;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValues()
    {
        return $this->values;
    }

    /**
     * @deprecated
     * @return mixed
     */
    public function getValueList()
    {
        return $this->getValues();
    }
}