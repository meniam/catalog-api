<?php
namespace Catalog\Interfaces;

interface InfoForShippingApi
{
    public function getCountryShortName();

    public function getPostalCode();
}