<?php

namespace Catalog;
use Catalog\EbayAPI\Product;
use Catalog\Exception\EmptyParam;
use Catalog\ValueObject\Price;
use Catalog\ValueObject\NullPrice;
use Catalog\Product\Condition;
use Catalog\Product\Validation\Ebay\Attributes;

abstract class EbayProductCreator extends ProductCreator
{

    public function __construct($response, array $errorsApi = array())
    {
        $this->response = $response;
        $this->errorsApi = $errorsApi;
        $this->product = new Product();
        $this->validation = new Attributes();
    }

    protected function parse()
    {
        $this->assignAmount();
        $this->assignAspects();
        $this->assignCategoryId();
        $this->assignCondition();
        $this->assignDescription();
        $this->assignTextDescription();
        $this->assignImageList();
        $this->assignName();
        $this->assignOriginalId();
        $this->assignPrice();
        $this->assignSeller();
        $this->assignVariation();
        $this->assignHitCountEbay();
        $this->assignPaymentMethodList();
        $this->assignLocated();
        $this->assignCountry();
        $this->assignBidCount();
        $this->assignBuyItNow();
        $this->assignErrorsApi();
        $this->assignSiteId();
        $this->product->freeze();
        return $this->product;
    }

    private function assignErrorsApi()
    {
        $this->product->errorsApi = (array) $this->errorsApi;
    }

    private function assignCondition()
    {
        if (empty($this->response['ConditionDisplayName'])) {
            $this->response['ConditionDisplayName'] = null;
        }
        if (empty($this->response['ConditionID'])) {
            $this->response['ConditionID'] = 0;
        }
        $this->product->condition = new Condition($this->response['ConditionID'],
            $this->response['ConditionDisplayName']);
    }

    private function assignDescription()
    {
        if (isset($this->response['Description'])) {
            $this->product->description = $this->validation->getCorrectDescription($this->response['Description']);
        }
    }

    private function assignTextDescription()
    {
        if (isset($this->response['TextDescription'])) {
            $this->product->textDescription = $this->validation->getCorrectDescription($this->response['TextDescription']);
        }
    }

    private function assignImageList()
    {
        if (!empty($this->response['PictureDetails']['PictureURL'])) {
            $imageList = $this->response['PictureDetails']['PictureURL'];
        } elseif (!empty($this->response['PictureURL'])) {
            $imageList = $this->response['PictureURL'];
        } elseif (!empty($this->response['PictureDetails']['GalleryURL'])) {
            $imageList = $this->response['PictureDetails']['GalleryURL'];
        } else {
            throw new EmptyParam('Default Image is empty or not exist');
        }
        $this->product->imageList = $this->validation->getCorrectImageDefault((array) $imageList);
    }

    private function assignSiteId()
    {
        if (empty($this->response['Site'])) {
            throw new EmptyParam('Site Id is empty or not exist');
        }
        $this->product->siteId = $this->validation->getShortSiteId($this->response['Site']);
    }


    private function assignPaymentMethodList()
    {
        if (empty($this->response['PaymentMethods'])) {
            throw new EmptyParam('Payment Methods is empty or not exist');
        }
        $this->product->paymentMethodList = (array) $this->response['PaymentMethods'];
    }

    private function assignSeller()
    {
        if (empty($this->response['Seller']) || !is_array($this->response['Seller'])) {
            throw new EmptyParam('Seller is not array or empty');
        }
        $this->product->seller = $this->validation->getSeller($this->response['Seller']);
    }

    private function assignName()
    {
        if (empty($this->response['Title'])) {
            throw new EmptyParam('Name is empty or not exist');
        }
        $this->product->name = (string) $this->response['Title'];
    }

    private function assignLocated()
    {
        if (empty($this->response['Location'])) {
            throw new EmptyParam('Location is empty or not exist');
        }
        $this->product->located = (string) $this->response['Location'];
    }

    private function assignCountry()
    {
        if (empty($this->response['Country'])) {
            throw new EmptyParam('Country is empty or not exist');
        }
        if ($this->response['Country'] == 'GB') {
            $this->response['Country'] = 'UK';
        }

        $this->product->country = (string) $this->response['Country'];
    }

    private function assignVariation()
    {
        $this->product->variations = [];
        $imageList = empty($this->response['Variations']['Pictures']) ? array() :$this->response['Variations']['Pictures'];
        if (isset($this->response['Variations']['Variation'])){
            $this->product->variations = $this->validation->getVariationList(
                    (array)$this->response['Variations']['Variation'],
                    $this->validation->getCorrectImageVariationList($imageList)
            );
        }
    }

    private function assignAspects()
    {
        $this->product->aspects = [];
        if (!empty($this->response['ItemSpecifics']['NameValueList'])) {
            $aspectList = $this->validation->getCorrectArray((array)$this->response['ItemSpecifics']['NameValueList'], 'Name');
            $this->product->aspects = $this->validation->getAspectList($aspectList);
        }
    }

    private function assignOriginalId()
    {
        if (!isset($this->response['ItemID']) || !is_numeric($this->response['ItemID'])) {
            throw new EmptyParam('OriginalID is not int or not exist');
        }
        $this->product->originalId = $this->response['ItemID'];
    }

    private function assignCategoryId()
    {
        if (!empty($this->response['PrimaryCategory']['CategoryID'])) {
            $categoryId = $this->response['PrimaryCategory']['CategoryID'];
        } elseif (!empty($this->response['PrimaryCategoryID'])) {
            $categoryId = $this->response['PrimaryCategoryID'];
        } else {
            throw new EmptyParam('Category is not numeric or not exist');
        }
        $this->product->categoryId = (int) $categoryId;
    }


    private function assignPrice()
    {
        if (!empty($this->response['SellingStatus']['CurrentPrice']['#'])) {
            $priceValue = $this->response['SellingStatus']['CurrentPrice']['#'];
        } else if (!empty($this->response['CurrentPrice']['#'])) {
            $priceValue = $this->response['CurrentPrice']['#'];
        } else {
            throw new EmptyParam('Price is not float or not exist');
        }
        if (!empty($this->response['Currency'])) {
            $currency = $this->response['Currency'];
        } elseif (!empty($this->response['CurrentPrice']['@currencyID'])) {
            $currency = $this->response['CurrentPrice']['@currencyID'];
        } else {
            throw new EmptyParam('Currency is empty or not exist');
        }
        $this->product->price = new Price((float) $priceValue, $currency);
    }

    private function assignAmount()
    {
        if (!isset($this->response['Quantity'])) {
            throw new EmptyParam('Quantity is not exist');
        }
        $this->product->amount = (int) $this->validation->getAmount($this->response);
    }

    private function assignHitCountEbay()
    {
        $this->product->hitCountEbay = 0;
        if (isset($this->response['HitCount'])) {
            $this->product->hitCountEbay = (int) $this->response['HitCount'];
        }
    }

    private function assignBidCount()
    {
        $this->product->bidCount = 0;
        if (isset($this->response['BidCount'])) {
            $this->product->bidCount = $this->response['BidCount'];
        }
    }

    private function assignBuyItNow()
    {
        $this->product->buyItNow = new NullPrice();
        if (isset($this->response['BuyItNowPrice']['#']) && isset($this->response['BuyItNowPrice']['@currencyID'])){
            $this->product->buyItNow = new Price((float)$this->response['BuyItNowPrice']['#'],
                $this->response['BuyItNowPrice']['@currencyID']);
        }
    }
}