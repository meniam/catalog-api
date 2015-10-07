<?php
namespace Catalog\Product;

use Catalog\EbayProductCreator;
use Catalog\ValueObject\Price;

class ProductShortInfo extends EbayProductCreator
{
    private $id;
    private $price;
    private $currency;
    private $name;
    private $categoryId;
    private $link;
    private $siteId;
    private $imageLink;
    private $bidCount;
    private $isAuction;
    private $shippingPrice;

    private $globalIds = [
        'EBAY-MOTOR' => 'MOTORS',
        'US' => 'US',
        'GB' => 'UK',
        'DE' => 'DE',
        'EBAY-US' => 'US',
        'EBAY-GB' => 'UK',
        'EBAY-DE' => 'DE',
        'EBAY-ENCA' => 'CA',
        'EBAY-AT' => 'AT',
        'EBAY-AU' => 'AU',
        'EBAY-IE' => 'IE',
        'EBAY-FRCA' => 'CAFR',
        'EBAY-ES' => 'ES',
        'EBAY-FR' => 'FR',
        'EBAY-IT' => 'IT'
    ];


    public function __construct($item)
    {
        $this->id = $item['itemId'];
        $this->price = $item['sellingStatus']['currentPrice']['#'];
        $this->currency = $item['sellingStatus']['currentPrice']['@currencyId'];
        $this->name = $item['title'];
        $this->categoryId = $item['primaryCategory']['categoryId'];
        $this->link = $item['viewItemURL'];
        $this->siteId = $this->globalIds[$item['globalId']];
        $this->imageLink = (array)$this->getSafeDate($this->getSafeDate($item, 'galleryInfoContainer'), 'galleryURL')
            + (array)$this->getSafeDate($item, 'galleryURL')
            + (array)$this->getSafeDate($item, 'galleryPlusPictureURL');
        $this->bidCount = 0;
        if (isset($item['sellingStatus']['bidCount'])) {
            $this->bidCount = (int) $item['sellingStatus']['bidCount'];
        }
        $this->isAuction = false;
        if (isset($item['listingInfo']['listingType']) && $item['listingInfo']['listingType'] == 'Auction') {
            $this->isAuction = true;
        }
        $this->setShippingCost($item);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    private function getSafeDate($data, $key)
    {
        if (!$data || !$key) {
            return null;
        }
        if (!empty($data[$key])) {
            return $data[$key];
        }
        return null;
    }

    /**
     * @return array
     */
    public function getGlobalIds()
    {
        return $this->globalIds;
    }

    /**
     * @return null
     */
    public function getImageLink()
    {
        return $this->imageLink;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @return mixed
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @return mixed
     */
    public function getBidCount()
    {
        return $this->bidCount;
    }

    public function isItAuction()
    {
        return $this->isAuction;
    }

    private function setShippingCost($item)
    {
        if (!isset($item['shippingInfo']['shippingServiceCost'])) {
            return;
        }
        $shippingBlock = $item['shippingInfo']['shippingServiceCost'];
        $this->shippingPrice = new Price($shippingBlock['#'], $shippingBlock['@currencyId']);
    }

    /**
     * @return Price
     */
    public function getShipping()
    {
        return $this->shippingPrice;
    }
}