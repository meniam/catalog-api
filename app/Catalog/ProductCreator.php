<?php
namespace Catalog;

abstract class ProductCreator
{
    protected $response;
    /**
     * @var $builder ProductBuilder
     */
    protected $builder;
    /**
     * @var $validation  \Catalog\Product\Validation\Ebay\Attributes
     */
    protected $validation;
    protected $errorsApi;

    abstract protected function parse();
}