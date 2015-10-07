<?php

class SkuNormalizerTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptySkuIsIncorrect()
    {
        $sku = new \Catalog\Sku('');
        $this->assertFalse($sku->isCorrect());
    }

    public function testStringWithLettersAndDigitsIsCorrectSku()
    {
        $sku = new \Catalog\Sku('780243ADF343');
        $this->assertTrue($sku->isCorrect());
    }

    public function testStringShorterThanSixSymbolsCantBeASku()
    {
        $sku = new \Catalog\Sku('78243');
        $this->assertFalse($sku->isCorrect());
    }

    public function testSkuMustHaveAtLeastOneDigit()
    {
        $sku = new \Catalog\Sku('ABCDEFG');
        $this->assertFalse($sku->isCorrect());
    }

    public function testSkuMustntHaveWords()
    {
        $sku = new \Catalog\Sku('306 Ounce');
        $this->assertFalse($sku->isCorrect());
    }

    public function testStringWithLettersOnlyLargerThanTenSymbolsCantBeASku()
    {
        $sku = new \Catalog\Sku('ABCDEFGHIJKLMNOPQRST');
        $this->assertFalse($sku->isCorrect());
    }

    public function testNormalizedValueTransformsLettersToTheUpperCase()
    {
        $sku = new \Catalog\Sku('780243adf343');
        $this->assertEquals('780243ADF343', $sku->getNormalizedValue());
    }

    public function testNormalizedValueHasNoSpecialSymbols()
    {
        $sku = new \Catalog\Sku('780-243/adf 343');
        $this->assertEquals('780243ADF343', $sku->getNormalizedValue());
    }
}