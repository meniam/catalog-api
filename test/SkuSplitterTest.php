<?php

class SkuSplitterTest extends \PHPUnit_Framework_TestCase
{
    public function testStringWithoutSeparatorsCreatesOneSku()
    {
        $sku = new \Catalog\SkuSplitter('AR.44545-343');
        $this->assertEquals([new \Catalog\Sku('AR.44545-343')], $sku->getSkus());
    }

    public function testSeparatedStringProducesFewSkus()
    {
        $sku = new \Catalog\SkuSplitter('AR.44545-343;BG3432424');
        $this->assertEquals([
            new \Catalog\Sku('AR.44545-343'),
            new \Catalog\Sku('BG3432424')
        ], $sku->getSkus());
    }

    public function testItSplitsSpaceSeparatedValues()
    {
        $sku = new \Catalog\SkuSplitter('0804324234 424342423434');
        $this->assertEquals([
            new \Catalog\Sku('0804324234'),
            new \Catalog\Sku('424342423434')
        ], $sku->getSkus());
    }

    public function testItSplitsSlashSeparatedValues()
    {
        $sku = new \Catalog\SkuSplitter('080 4324234/424342423434');
        $this->assertEquals([
            new \Catalog\Sku('080 4324234'),
            new \Catalog\Sku('424342423434')
        ], $sku->getSkus());
    }

    public function testCombineSpaceSeparated()
    {
        $sku = new \Catalog\SkuSplitter('4  00100 70997  9');
        $this->assertEquals([
            new \Catalog\Sku('4  00100 70997  9')
        ], $sku->getSkus());
    }

    public function testSpecialSymbols()
    {
        $sku = new \Catalog\SkuSplitter('Keeper 05561 6\' x 1" Retractable Ratchet Tie-Down, 2 Pack');
        $this->assertEquals([], $sku->getSkus());
    }

    public function testHasLowCorrectness()
    {
        $sku = new \Catalog\SkuSplitter('LED Off Road Light 4X4 Work Light Waterproof 27W 12V 6000K 60 Deg Flood,Genssi');
        $this->assertEquals([], $sku->getSkus());
    }
}