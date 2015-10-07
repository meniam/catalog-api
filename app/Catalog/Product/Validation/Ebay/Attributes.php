<?php

namespace Catalog\Product\Validation\Ebay;

use Catalog\Exception\EmptyParam;
use Catalog\Product\Aspect;
use Catalog\ValueObject\Price;
use Catalog\Product\Seller;
use Catalog\Product\ShippingCost;
use Catalog\Product\ShippingCostContainer;
use Catalog\Product\Validation;
use Catalog\Product\Variation;
use Catalog\ValueObject\NullPrice;

class Attributes
{
    private $siteIdList = [
        'Australia' => 'AU',
        'Austria' => 'AT',
        'Belgium_Dutch' => 'BENL',
        'Belgium_French' => 'BEFR',
        'Canada' => 'CA',
        'CanadaFrench' => 'CAFR',
        'China' => 'CN',
        'eBayMotors' => 'MOTORS',
        'France' => 'FR',
        'Germany' => 'DE',
        'HongKong' => 'HK',
        'India' => 'IN',
        'Ireland' => 'IE',
        'Italy' => 'IT',
        'Malaysia' => 'MY',
        'Netherlands' => 'NL',
        'Philippines' => 'PH',
        'Poland' => 'PL',
        'Singapore' => 'SG',
        'Spain' => 'ES',
        'Sweden' => 'SE',
        'Switzerland' => 'CH',
        'Taiwan' => 'TW',
        'UK' => 'UK',
        'US' => 'US',
    ];

    /**
     * @param string $description
     * @return string
     */
    public function getCorrectDescription($description)
    {
        $description = preg_replace('#<!--[^(--)]*-->#uis', '', $description);
        $description = preg_replace('|<a(.*)href=|Uis', '<noindex><a rel="noindex,nofollow" target="_blank"$1href=', $description);
        $description = preg_replace('|</a>|Uis', '</a></noindex>', $description);
        $description = preg_replace('/(on[A-Za-z]*=".*")|(on[A-Za-z]*=\'.*\')/U', '', $description);
        return preg_replace('|<script.+?</script>|uis', ' ', $description);
    }


    /**
     * @param array $response
     * @return int
     */
    public function getAmount(array $response)
    {
        $amountAll = (int)$response['Quantity'];
        $amountSold = empty($response['QuantitySold']) ? 0 : (int)$response['QuantitySold'];
        return (int)($amountAll - $amountSold);
    }


    public function isAuctionSingle(array $response)
    {
        if (empty($response['ListingType']) || empty($response['ListingStatus'])) {
            return false;
        }
        if ($response['ListingType'] == 'FixedPriceItem' && $response['ListingStatus'] != 'Completed') {
            return false;
        }
        return true;
    }

    public function isAuctionTrading(array $response)
    {
        if (empty($response['ListingType']) || empty($response['SellingStatus']['ListingStatus'])) {
            return false;
        }
        if (($response['ListingType'] == 'FixedPriceItem' && $response['SellingStatus']['ListingStatus'] != 'Completed') ) {
            return false;
        }
        return true;
    }

    /**
     * @param array $aspectList
     * @return array
     * @throws \Catalog\Exception\EmptyParam
     */
    public function getAspectList(array $aspectList)
    {
        $aspectItemList = array();
        foreach ($aspectList as $aspect) {
            if (!isset($aspect['Name'])) {
                throw new EmptyParam('Aspect name is not exist');
            }
            if (!isset($aspect['Value'])) {
                throw new EmptyParam('Aspect value is not exist');
            }
            $aspectItemList[] = new Aspect($aspect['Name'], (array)$aspect['Value']);
        }
        return $aspectItemList;
    }

    /**
     * @param array $variationList
     * @param array $imageList
     * @return array
     */
    public function getVariationList(array $variationList, array $imageList = array())
    {
        $variationItemList = array();
        $elementModel = new Validation\Ebay\Variation\Element();
        $variationList = $this->getCorrectArray($variationList, 'StartPrice');
        foreach ($variationList as $variation) {
            $this->validationVariation($variation);
            $price = new Price($variation['StartPrice']['#'], $variation['StartPrice']['@currencyID']);
            $amount = empty($variation['SellingStatus']['QuantitySold']) ? (int)$variation['Quantity']
                : (int)($variation['Quantity'] - $variation['SellingStatus']['QuantitySold']);
            $valueList = $elementModel->fillingVariationValue($variation['VariationSpecifics']['NameValueList'], $imageList);
            $variationItemList[] = new Variation($price, $amount, $valueList);
        }
        return $variationItemList;
    }

    public function getCorrectImageDefault(array $imageList)
    {
        return  array_map(function ($src) {
            if (preg_match('#http://[^/]*ebay#si', $src)) {
                $src =  preg_replace('#\d+\.jpg.*$#si', '3.jpg', $src);
            }
            return $src;
        }, $imageList);
    }

    public function getCorrectImageVariationList(array $imageList)
    {
        $correctImageList = array();
        if (!isset($imageList['VariationSpecificPictureSet']) || !isset($imageList['VariationSpecificName'])) {
            return array();
        }
        $correctImageList[$imageList['VariationSpecificName']] = $imageList['VariationSpecificPictureSet'];
        foreach ((array)$imageList['VariationSpecificPictureSet'] as $valueImageList) {
            if (!isset($valueImageList['PictureURL']) || !isset($valueImageList['VariationSpecificValue'])) {
                return array();
            }
            $correctImageList[$imageList['VariationSpecificName']][$valueImageList['VariationSpecificValue']]
                = array_map(function ($src) {
                if (preg_match('#http://[^/]*ebay#si', $src)) {
                    $src = preg_replace('#\~\~60\_\d+\.jpg.*$#si', '~~60_3.jpg', $src);
                }
                return $src;
            }, (array)$valueImageList['PictureURL']);
        }
        return $correctImageList;
    }

    public function getShortSiteId($siteId)
    {
        if (!array_key_exists($siteId, $this->siteIdList)) {
            throw new EmptyParam("SiteID {$siteId} not found");
        }
        return $this->siteIdList[$siteId];
    }

    public function getSeller(array $sellerInfo)
    {
        if (empty($sellerInfo['UserID'])) {
            throw new EmptyParam('UserID is empty or not exist');
        }
        if (!isset($sellerInfo['FeedbackScore']) || !is_numeric($sellerInfo['FeedbackScore']) ) {
            throw new EmptyParam('FeedbackScore is empty or not numeric');
        }
        if (!isset($sellerInfo['PositiveFeedbackPercent'])) {
            throw new EmptyParam('PositiveFeedbackPercent is empty or not float');
        }
        return new Seller($sellerInfo);

    }

    public function getShippingCostSummaryForTrading(array $shippingCostSummary = array())
    {
        $shippingCostContainer = new ShippingCostContainer();
        $shippingCostContainer->setServicePrice(new NullPrice());
        $shippingCostContainer->setListedServicePrice(new NullPrice());

        if (!isset($shippingCostSummary['ShippingType'])) {
            return new ShippingCost($shippingCostContainer);
        }
        if (isset($shippingCostSummary['ShippingServiceName'])) {
            $shippingCostContainer->setName($shippingCostSummary['ShippingServiceName']);
        }
        if (isset($shippingCostSummary['ShippingType'])) {
            $shippingCostContainer->setType($shippingCostSummary['ShippingType']);
        }
        if (isset($shippingCostSummary['ShippingServiceCost']['#'])) {
            $shippingCostContainer->setServicePrice(
                new Price(
                    $shippingCostSummary['ShippingServiceCost']['#'],
                    $shippingCostSummary['ShippingServiceCost']['@currencyID']
                )
            );
        }
        if (isset($shippingCostSummary['ListedShippingServiceCost']['#'])) {
            $shippingCostContainer->setListedServicePrice(
                new Price(
                    $shippingCostSummary['ListedShippingServiceCost']['#'],
                    $shippingCostSummary['ListedShippingServiceCost']['@currencyID']
                )
            );
        }
        return new ShippingCost($shippingCostContainer);
    }

    public function getShippingCostSummaryForSingle(array $shippingCostSummary = array())
    {
        $shippingCostContainer = new ShippingCostContainer();
        $shippingCostContainer->setServicePrice(new NullPrice());
        $shippingCostContainer->setListedServicePrice(new NullPrice());
        if (isset($shippingCostSummary['ShippingServiceName'])) {
            $shippingCostContainer->setName($shippingCostSummary['ShippingServiceName']);
        }
        if (isset($shippingCostSummary['ShippingType'])) {
            $shippingCostContainer->setType($shippingCostSummary['ShippingType']);
        }
        if (isset($shippingCostSummary['ShippingServiceCost'])) {
            $shippingCostContainer->setServicePrice(
                new Price(
                    $shippingCostSummary['ShippingServiceCost']['#'],
                    $shippingCostSummary['ShippingServiceCost']['@currencyID']
                )
            );
        }
        if (isset($shippingCostSummary['ListedShippingServiceCost'])) {
            $shippingCostContainer->setListedServicePrice(
                new Price(
                    $shippingCostSummary['ListedShippingServiceCost']['#'],
                    $shippingCostSummary['ListedShippingServiceCost']['@currencyID']
                )
            );
        }
        return [new ShippingCost($shippingCostContainer)];
    }

    private function validationVariation($variation)
    {
        if (!isset($variation['StartPrice']['#'])) {
            new EmptyParam('Variation has not price');
        }

        if (!isset($variation['StartPrice']['#currencyID'])) {
            new EmptyParam('Variation has not currency');
        }

        if (!isset($variation['Quantity'])) {
            new EmptyParam('Variation has not amount');
        }

        if (!isset($variation['VariationSpecifics']['NameValueList'])) {
            new EmptyParam('Variation has not value list');
        }
    }

    public function getAllShippingService($shippingDetails)
    {
        $shippingDetails['ShippingServiceOptions']['ShippingType'] = isset($shippingDetails['ShippingType'])
            ? $shippingDetails['ShippingType']
            : null;
        $shippingCostList[] = $this->getShippingCostSummaryForTrading($shippingDetails['ShippingServiceOptions']);
        if (isset($shippingDetails['InternationalShippingServiceOption'])) {
            $shippingDetails['InternationalShippingServiceOption']['ShippingType'] = $shippingDetails['ShippingServiceOptions']['ShippingType'];
            $shippingCostList[] = $this->getShippingCostSummaryForTrading($shippingDetails['InternationalShippingServiceOption']);
        }
        return $shippingCostList;
    }

    public function getCorrectArray(array $array, $suspectedItem)
    {
        if (array_key_exists($suspectedItem, $array)) {
            return [$array];
        }
        return $array;
    }
}