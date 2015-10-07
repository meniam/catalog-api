<?php

namespace Catalog;

abstract class Freezable
{
    protected $allowedFields = [];
    private $container = [];
    private $isFrozen = false;

    /**
     * @param string $name
     * @param mixed $value
     * @throws FrozenObjectChange
     * @throws UnknownField
     */
    public function __set($name, $value)
    {
        if ($this->isFrozen) {
            throw new \Catalog\Exception\FrozenObjectChange();
        }
        if (!in_array($name, $this->allowedFields)) {
            throw new \Catalog\Exception\UnknownField($name);
        }
        $this->container[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws UnknownField
     */
    public function __get($name)
    {
        if (!in_array($name, $this->allowedFields)) {
            throw new \Catalog\Exception\UnknownField($name);
        }
        if (!isset($this->container[$name])) {
            return null;
        }
        return $this->container[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return in_array($name, $this->allowedFields);
    }

    public function freeze()
    {
        $this->isFrozen = true;
    }

    /**
     * @return bool
     */
    public function isFrozen()
    {
        return $this->isFrozen;
    }

    public function toArray()
    {
        return $this->container;
    }

    public function isEmpty()
    {
        return empty($this->container);
    }
}