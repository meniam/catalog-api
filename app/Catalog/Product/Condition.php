<?php
namespace Catalog\Product;

class Condition
{
    private $id;

    private $name;

    /**
     * @param int $id
     * @param string $name
     */
    public function __construct($id, $name)
    {
        $this->name = (string)$name;
        $this->id = (int)$id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}