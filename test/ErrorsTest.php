<?php
use Catalog\Exception;
class ErrorsTest extends \PHPUnit_Framework_TestCase
{

    public function testException()
    {
        $this->setExpectedException('\Catalog\Exception\ApiError');
        $mock = $this->getMock('\Catalog\EbayAPI\Client', null, array(1111));
        $mock->getProductById(261177255);
    }
}