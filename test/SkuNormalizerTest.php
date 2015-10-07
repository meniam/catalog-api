<?php

class SkuNormalizerTest extends \PHPUnit_Framework_TestCase
{


    public function testEmptyString()
    {
        $this->assertEquals([], (new \Catalog\SkuNormalizer())->transform(''));
    }
    public function testNull()
    {
        $this->assertEquals([], (new \Catalog\SkuNormalizer())->transform(null));
    }

    public function testCorrectSku()
    {
        $this->assertEquals(['AR3423424'], (new \Catalog\SkuNormalizer())->transform('AR3423424'));
    }

    public function testLowercaseSku()
    {
        $this->assertEquals(['AR3423424'], (new \Catalog\SkuNormalizer())->transform('ar3423424'));
    }

    public function testPartedSku()
    {
        $this->assertEquals(['AR7593CR'], (new \Catalog\SkuNormalizer())->transform('AR.75-93 CR'));
    }

    public function testManySkus()
    {
        $this->assertEquals(['AR7593CR', 'LN45453343'],
            (new \Catalog\SkuNormalizer())->transform('AR.75-93 CR, LN_4545.3343'));
    }

    public function testSkusPartedWithSpaces()
    {
        $this->assertEquals(['AR7593CR', 'LN45453343'],
            (new \Catalog\SkuNormalizer())->transform('AR.75-93-CR LN_4545.3343'));
    }

    public function testSku()
    {
        $this->assertFalse(
            (new \Catalog\SkuNormalizer())->isSku('Best Case For Galaxy Note III'));
    }

    public function testSkusPartedWithSlashes()
    {
        $this->assertEquals(['AR7593CR', 'LN45453343'],
            (new \Catalog\SkuNormalizer())->transform('AR.75-93-CR/LN_4545.3343'));
    }

    public function testSkuWithSlashes()
    {
        $this->assertEquals(['HORTWBE290'],
            (new \Catalog\SkuNormalizer())->transform('horTW/BE290'));
    }

    public function testSkuWith()
    {
        $this->assertEquals(['ENFIELDD613'],
            (new \Catalog\SkuNormalizer())->transform('Enfield D613 garage door bolts'));
    }

    public function testSkus()
    {
        $this->assertEquals(['7593CRAR', 'A45453343LNS', 'AR7593CR', 'AR7593CR7DS'],
            (new \Catalog\SkuNormalizer())->transform('75-93-CR AR, a 4545.3343/LN S, AR.75-93-CR,  AR.75-93-CR7 D S'));
    }

    public function testSkusWithSpaces()
    {
        $this->assertEquals(['7593CRAR', '45453343LNS', 'SAR7593CR', 'AR7593CR7D'],
            (new \Catalog\SkuNormalizer())->transform('75-93-CRAR 4545.3343/LNS SAR.75-93/CR  AR.75-93-CR7D'));
    }

    public function testIsSku()
    {
        $this->assertTrue((new \Catalog\SkuNormalizer())->isSku('AR.76-39-CR'));
    }

    public function testIsSkuEmpty()
    {
        $this->assertFalse((new \Catalog\SkuNormalizer())->isSku(''));
    }

    public function testIsSkuWithSpecSymbol()
    {
        $this->assertFalse((new \Catalog\SkuNormalizer())->isSku('45453343LNS&5'));
    }

    public function testIsSkuShort()
    {
        $this->assertFalse((new \Catalog\SkuNormalizer())->isSku('AZ124'));
    }

}