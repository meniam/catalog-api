<?php
use Catalog\Exception;
class TypesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var $response \Catalog\AbstractProduct
     */
    protected $response;

    protected function setUp()
    {
        $response = json_decode(file_get_contents(__DIR__ . '/ebay.json'), true);
        $creator = $this->getMock('\Catalog\EbayProductCreator', null, array($response['Item']));
        $this->response = $creator->parse();

    }

    public function testOriginalId()
    {
        $this->assertInternalType('string', $this->response->getOriginalId());
    }

    public function testOriginalLink()
    {
        $this->assertInternalType('string', $this->response->getOriginalLink());
    }

    public function testCategoryId()
    {
        $this->assertInternalType('string', $this->response->getCategoryId());
    }

    public function testCondition()
    {
        $this->assertInternalType('object', $this->response->getCondition());
    }

    public function testDescription()
    {
        $this->assertInternalType('string', $this->response->getDescription());
    }

    public function testExpireAt()
    {
        $this->assertInstanceOf('DateTime', $this->response->getExpirationTime());
    }

    public function testImageList()
    {
        $this->assertInternalType('array', $this->response->getImageList());
    }

    public function testPaymentMethodList()
    {
        $this->assertInternalType('array', $this->response->getPaymentMethodList());
    }

    public function testIsAuction()
    {
        $this->assertInternalType('boolean', $this->response->isAuction());
    }

    public function testSeller()
    {
        $this->assertInternalType('object', $this->response->getSeller());
    }

    public function testSellerName()
    {
        $this->assertInternalType('string', $this->response->getSeller()->getName());
    }

    public function testName()
    {
        $this->assertInternalType('string', $this->response->getName());
    }

    public function testLocated()
    {
        $this->assertInternalType('string', $this->response->getLocated());
    }

    public function testCountry()
    {
        $this->assertInternalType('string', $this->response->getCountry());
    }

    public function testVariation()
    {
        $this->assertInternalType('array', $this->response->getVariationList());
    }

    public function testAspect()
    {
        $this->assertInternalType('array', $this->response->getAspectList());
    }

    public function testPrice()
    {
        $this->assertInternalType('float', $this->response->getPrice());
    }

    public function testCurrency()
    {
        $this->assertInternalType('string', $this->response->getCurrency());
    }

    public function testHitCountEbay()
    {
        $this->assertInternalType('integer', $this->response->getHitCountEbay());
    }

    public function testBidCount()
    {
        $this->assertInternalType('integer', $this->response->getBidCount());
    }

    public function testBuyItNow()
    {
        $this->assertInternalType('object', $this->response->getBuyItNow());
    }

    protected function tearDown()
    {
        $this->response = NULL;
    }
}