<?php

namespace Catalog\EbayAPI;

use Catalog\EbayItem\Single;
use Catalog\Exception\ApiError;
use Catalog\Exception\BadRequest;
use Catalog\Exception\EmptyRequest;
use Catalog\ItemList;
use Catalog\Product\Seller;
use Catalog\Product\Validation\Ebay\Attributes;
use Catalog\ProductStatus;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use  \Catalog\Interfaces\InfoForShippingApi;

class Client
{
    const SHOPPING_URL = 'http://open.api.ebay.com/shopping?';
    const TRADING_URL = 'https://api.ebay.com/ws/api.dll?';
    const FINDING_URL = 'http://svcs.ebay.com/services/search/FindingService/v1?';
    const CALCULATED_URL = 'http://payments.ebay.%s/ws2/eBayISAPI.dll?EmitBuyerShippingCalculator&';
    const CALCULATED = 'Calculated';
    const SHOPPING_API_VERSION = 889;

    private $serializer;

    private $errorNotCritical = array('1.15', '1.16', '1.17', '1.24', '1.29',
        '1.30', '10.14', '10.64', '10.65',
        '10.77', '10.78', '10.79', '10.80', '10.6',
        '10.86', '10.91', '10.92', '10.93',
        '10.94', '10.96'
    );

    private $domain = ['US' => 'com', 'DE' => 'de', 'UK' => 'co.uk'];

    private $defaultProductSelectors = [
        'Details', 'SearchDetails', 'ItemSpecifics', 'Compatibility', 'ShippingCosts', 'Variations'];

    private $siteIds = [
        'AT' => ['code' => 16, 'globalId' => 'EBAY-AT'],
        'AU' => ['code' => 15, 'globalId' => 'EBAY-AU'],
        'BEFR' => ['code' => 23, 'globalId' => 'EBAY-FRBE'],
        'BENL' => ['code' => 123, 'globalId' => 'EBAY-NLBE'],
        'CAFR' => ['code' => 210, 'globalId' => 'EBAY-FRCA'],
        'CH' => ['code' => 193, 'globalId' => 'EBAY-CH'],
        'DE' => ['code' => 77, 'globalId' => 'EBAY-DE'],
        'CA' => ['code' => 2, 'globalId' => 'EBAY-ENCA'],
        'ES' => ['code' => 186, 'globalId' => 'EBAY-ES'],
        'FR' => ['code' => 71, 'globalId' => 'EBAY-FR'],
        'HK' => ['code' => 201, 'globalId' => 'EBAY-HK'],
        'IE' => ['code' => 205, 'globalId' => 'EBAY-IE'],
        'IN' => ['code' => 203, 'globalId' => 'EBAY-IN'],
        'IT' => ['code' => 101, 'globalId' => 'EBAY-IT'],
        'MOTORS' => ['code' => 100, 'globalId' => 'EBAY-US'],
        'MY' => ['code' => 207, 'globalId' => 'EBAY-MY'],
        'NL' => ['code' => 146, 'globalId' => 'EBAY-NL'],
        'PH' => ['code' => 211, 'globalId' => 'EBAY-PH'],
        'PL' => ['code' => 212, 'globalId' => 'EBAY-PL'],
        'SG' => ['code' => 216, 'globalId' => 'EBAY-SG'],
        'UK' => ['code' => 3, 'globalId' => 'EBAY-GB'],
        'GB' => ['code' => 3, 'globalId' => 'EBAY-GB'],
        'US' => ['code' => 0, 'globalId' => 'EBAY-US'],
        'USA' => ['code' => 0, 'globalId' => 'EBAY-US']
    ];

    public function __construct(\Buzz\Browser $httpClient, array $keys)
    {
        $this->keys = $keys;
        $this->httpClient = $httpClient;

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new GetSetMethodNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function getProduct($id, $countryShortName = 'US')
    {
        $request = new Request($this->httpClient, $this->keys);
        $responses = $request->performParallel([
            'with_description' => [
                'url' => self::SHOPPING_URL,
                'params' => [
                    'callname' => 'GetSingleItem',
                    'responseencoding' => 'XML',
                    'appid' => '%APP_ID%',
                    'siteid' => $this->getCountryCodeByShortName($countryShortName),
                    'version' => self::SHOPPING_API_VERSION,
                    'ItemID' => $id,
                    'IncludeSelector' => implode(',', array_merge(['Description'], $this->defaultProductSelectors))
                ]
            ],
            'with_text_description' => [
                'url' => self::SHOPPING_URL,
                'params' => [
                    'callname' => 'GetSingleItem',
                    'responseencoding' => 'XML',
                    'appid' => '%APP_ID%',
                    'siteid' => $this->getCountryCodeByShortName($countryShortName),
                    'version' => self::SHOPPING_API_VERSION,
                    'ItemID' => $id,
                    'IncludeSelector' => 'TextDescription'
                ]
            ]
        ]);
        try {
            $responseWithDescription = $this->decode($responses['with_description']);
            $responseWithTextDescription = $this->decode($responses['with_text_description']);
        } catch (ApiError $e) {
            throw new ApiError('Product' . $id . "\n" . $e->getMessage());
        }
        if (!isset($responseWithDescription['Item']) || !isset($responseWithTextDescription['Item'])) {
            throw new EmptyRequest('Response is empty.');
        }
        $response = $responseWithDescription;
        $response['Item']['TextDescription'] = '';
        if (isset($responseWithTextDescription['Item']['Description'])) {
            $response['Item']['TextDescription'] = $responseWithTextDescription['Item']['Description'];
        }
        return (new Single($response['Item']))->parse();
    }

    public function getManyProducts(array $ids, $countryShortName = 'US')
    {
        if (count($ids) > 20) {
            throw new BadRequest('products count is more than 20');
        }
        $request = new Request($this->httpClient, $this->keys);
        $responses = $request->performParallel([
            'with_description' => [
                'url' => self::SHOPPING_URL,
                'params' => [
                    'callname' => 'GetMultipleItems',
                    'responseencoding' => 'XML',
                    'appid' => '%APP_ID%',
                    'siteid' => $this->getCountryCodeByShortName($countryShortName),
                    'version' => self::SHOPPING_API_VERSION,
                    'ItemID' => implode(',', $ids),
                    'IncludeSelector' => implode(',', array_merge(['Description'], $this->defaultProductSelectors))
                ]
            ],
            'with_text_description' => [
                'url' => self::SHOPPING_URL,
                'params' => [
                    'callname' => 'GetMultipleItems',
                    'responseencoding' => 'XML',
                    'appid' => '%APP_ID%',
                    'siteid' => $this->getCountryCodeByShortName($countryShortName),
                    'version' => self::SHOPPING_API_VERSION,
                    'ItemID' => implode(',', $ids),
                    'IncludeSelector' => 'TextDescription'
                ]
            ]
        ]);
        $responseWithDescription = $this->decode($responses['with_description']);
        $responseWithTextDescription = $this->decode($responses['with_text_description']);
        if (!isset($responseWithDescription['Item']) || !isset($responseWithTextDescription['Item'])) {
            throw new EmptyRequest('Response is empty.');
        }
        $productList = new ProductList($responseWithDescription, $responseWithTextDescription);
        return $productList->toObjects();
    }

    public function getManyProductsUpdate(array $ids, $countryShortName = 'USA')
    {
        if (count($ids) > 20) {
            throw new BadRequest('products count is more than 20');
        }
        $response = $this->call(self::SHOPPING_URL, [
                    'callname' => 'GetMultipleItems',
                    'responseencoding' => 'XML',
                    'appid' => '%APP_ID%',
                    'siteid' => $this->getCountryCodeByShortName($countryShortName),
                    'version' => self::SHOPPING_API_VERSION,
                    'ItemID' => implode(',', $ids),
                    'IncludeSelector' => implode(',', ['Details', 'ItemSpecifics', 'Variations', 'ShippingCosts'])
        ]);
        if (!isset($response['Item'])) {
            throw new EmptyRequest('Response is empty.');
        }
        return (new ProductUpdateList($response))->toObjects();
    }

    public function getProductStatus($id, $siteId)
    {
        $response = $this->call(self::SHOPPING_URL, [
            'callname' => 'GetItemStatus',
            'responseencoding' => 'XML',
            'appid' => '%APP_ID%',
            'siteid' => $siteId,
            'version' => self::SHOPPING_API_VERSION,
            'ItemID' => $id
        ]);
        if (!isset($response['Item'])) {
            throw new EmptyRequest('Response is empty.');
        }
        return (new ProductStatus())->getStatus($response['Item']);
    }

    public function getSeller($name)
    {
        $response = $this->call(self::SHOPPING_URL, [
            'callname' => 'GetUserProfile',
            'responseencoding' => 'XML',
            'appid' => '%APP_ID%',
            'siteid' => '0',
            'version' => self::SHOPPING_API_VERSION,
            'UserID' => $name
        ]);
        if (!isset($response['User'])) {
            throw new EmptyRequest('Response is empty');
        }
        return (new Seller($response['User']));
    }


    public function getShipping($id, InfoForShippingApi $infoForShipping, $amount = 1)
    {
        $response = $this->call(self::SHOPPING_URL, [
            "callname" => "GetShippingCosts",
            "responseencoding" => "XML",
            "appid" => '%APP_ID%',
            "DestinationCountryCode" => $infoForShipping->getCountryShortName(),
            "destinationZipCode" => $infoForShipping->getPostalCode(),
            "QuantitySold" => $amount,
            "version" => self::SHOPPING_API_VERSION,
            "ItemID" => $id]);
        if (!isset($response['ShippingCostSummary'])) {
            throw new EmptyRequest("Request is empty. Product id {$id}");
        }
        if (!is_array($response['ShippingCostSummary'])) {
            return (new Attributes())->getShippingCostSummaryForTrading(
                [
                    'ShippingType' => null,
                    'ShippingServiceCost' => [
                        '#' => null,
                        '@currencyID' => null
                    ],
                    'ShippingServiceName' => null
                ]
            );
        }
        $shipping = (new Attributes())->getShippingCostSummaryForTrading($response['ShippingCostSummary']);
        if ($shipping->getType() == self::CALCULATED) {
            $shipping = $this->getCalculated($id, $infoForShipping, $amount);
        }
        return $shipping;
    }

    public function getCalculated($id, InfoForShippingApi $infoForShipping, $amount = 1)
    {
        $price = null;
        $currency = null;
        $name = null;
        $response = $this->call(
            sprintf(self::CALCULATED_URL, $this->getSubdomainForUrlCarculated($infoForShipping->getCountryShortName())),
            [
                'itemId' =>  $id,
                'destinationCountry' => $infoForShipping->getCountryShortName(),
                'quantity' => $amount,
                'destinationZipCode' => $infoForShipping->getPostalCode(),
                'intl' => 'true',
                'Calculate' => 1,
                'transactionid' => 0
            ],
            false
        );
        if (preg_match('#Handling:[^>]*</td>[^>]*<[^>]*>[^>]*<b>([^>]+)<\/b>#uis', $response, $matches)) {
            preg_match("#<td[^>]*align=[\"|']left[\"|'][^>]*>[^<b>]*<b>([^>]*)<#uis", $response, $name);
            $name = $name[1];
            $price = (float)preg_replace("#[^0-9\.,]#uis", "", $matches[1]);
        }
        return (new Attributes())->getShippingCostSummaryForTrading(
            [
                'ShippingType' => self::CALCULATED,
                'ShippingServiceCost' => [
                    '#' => $price,
                    '@currencyID' => 'USD'
                ],
                'ShippingServiceName' => $name
            ]
        );
    }

    public function findProducts(SearchCondition $condition)
    {
        $response = $this->call(self::FINDING_URL, array_merge([
            'OPERATION-NAME' => 'findItemsAdvanced',
            'RESPONSE-DATA-FORMAT' => 'XML',
            'SECURITY-APPNAME' => '%APP_ID%',
            'X-EBAY-SOA-GLOBAL-ID' => 'EBAY-US',
            'REST-PAYLOAD' => true,
            'paginationInput.entriesPerPage' => 100
        ], $condition->toArray()));
        if (!isset($response['searchResult'])) {
            throw new EmptyRequest("Response is empty.");
        }
        return (new ItemList($response['searchResult'], $response['paginationOutput']['totalEntries']));
    }

    public function findProductsBySku($sku, $page = 1)
    {
        $response = $this->call(self::FINDING_URL, array_merge([
            'OPERATION-NAME' => 'findItemsByKeywords',
            'RESPONSE-DATA-FORMAT' => 'XML',
            'SECURITY-APPNAME' => '%APP_ID%',
            'X-EBAY-SOA-GLOBAL-ID' => 'EBAY-US',
            'REST-PAYLOAD' => true,
            'paginationInput.entriesPerPage' => 100,
            'keywords' => $sku,
            'pageNumber' => (int)$page
        ]));
        if (!isset($response['searchResult'])) {
            throw new EmptyRequest("Response is empty.");
        }
        return (new ItemList($response['searchResult'], $response['paginationOutput']['totalEntries']));
    }

    public function getCategoryHistograms($id, $country)
    {
        $response = $this->call(self::FINDING_URL, [
            'OPERATION-NAME' => 'getHistograms',
            'RESPONSE-DATA-FORMAT' => 'XML',
            'SECURITY-APPNAME' => '%APP_ID%',
            'X-EBAY-SOA-GLOBAL-ID' => $this->siteIds[$country]['globalId'],
            'REST-PAYLOAD' => true,
            'categoryId' => $id
        ]);
        if (isset($response['Errors'])) {
            $results = $this->checkErrors($response);
            throw new ApiError(implode(', ', $results));
        }
        if (!isset($response['aspectHistogramContainer'])) {
            return new AspectHistogram([]);
        }
        return new AspectHistogram($response['aspectHistogramContainer']);
    }

    public function getAspectHistogram(SearchCondition $condition)
    {
        $response = $this->call(self::FINDING_URL, array_merge([
            'OPERATION-NAME' => 'findItemsAdvanced',
            'RESPONSE-DATA-FORMAT' => 'XML',
            'SECURITY-APPNAME' => '%APP_ID%',
            'REST-PAYLOAD' => true,
            'outputSelector' => 'AspectHistogram'
        ], $condition->toArray()));
        if (isset($response['Errors'])) {
            $results = $this->checkErrors($response);
            throw new ApiError(implode(', ', $results));
        }
        if (!isset($response['aspectHistogramContainer'])) {
            return new AspectHistogram([]);
        }
        return new AspectHistogram($response['aspectHistogramContainer']);
    }

    public function getCategoryHistogram(SearchCondition $condition)
    {
        $response = $this->call(self::FINDING_URL, array_merge([
            'OPERATION-NAME' => 'findItemsAdvanced',
            'RESPONSE-DATA-FORMAT' => 'XML',
            'SECURITY-APPNAME' => '%APP_ID%',
            'paginationInput.entriesPerPage' => 1,
            'outputSelector' => 'CategoryHistogram',
            'REST-PAYLOAD' => true
        ], $condition->toArray()));
        if (isset($response['Errors'])) {
            $results = $this->checkErrors($response);
            throw new ApiError(implode(', ', $results));
        }
        if (!isset($response['categoryHistogramContainer'])) {
            return new CategoryHistogram([]);
        }
        return new CategoryHistogram($response['categoryHistogramContainer']);
    }

    private function call($url, $params, $decode = true)
    {
        $request = new Request($this->httpClient, $this->keys);
        $response = $request->perform($url, $params);
        if ($decode) {
            $response =  $this->decode($response->getContent());
        }
        return $response;
    }

    private function decode($response)
    {
        $result = $this->serializer->decode($response, 'xml');
        $errors = $this->checkErrors($result);
        if ($errors['critical']) {
            throw new ApiError(implode('. ', $errors['critical']));
        }
        if ($errors['warnings']) {
            $result['warnings'] = $errors['warnings'];
        }
        return $result;
    }

    private function checkErrors($response)
    {
        if (!isset($response['Errors'])) {
            return ['critical' => [], 'warnings' => []];
        }
        $warnings = [];
        $critical = [];
        if (!empty($response['Errors']['ShortMessage'])) {
            if (in_array($response['Errors']['ErrorCode'], $this->errorNotCritical)) {
                $warnings[] = $response['Errors']['ShortMessage'];
            } else {
                $critical[] = $response['Errors']['ShortMessage'];
            }
        } else {
            foreach ($response['Errors'] as $error) {
                if (in_array($error['ErrorCode'], $this->errorNotCritical)) {
                    $warnings[] = $error['ShortMessage'];
                    continue;
                }
                $critical[] = $error['ShortMessage'];
            }
        }
        return ['critical' => $critical, 'warnings' => $warnings];
    }

    public function getCountryCodeByShortName($shortName)
    {
        if (!array_key_exists($shortName, $this->siteIds)) {
            $shortName = 'US';
        }
        return $this->siteIds[$shortName]['code'];
    }

    public function getGlobalIdByCountryCode($shortName)
    {
        if (!array_key_exists($shortName, $this->siteIds)) {
            $shortName = 'US';
        }
        return $this->siteIds[$shortName]['globalId'];
    }

    private function getSubdomainForUrlCarculated($countryShortName)
    {
        if (!array_key_exists($countryShortName, $this->domain)) {
            $countryShortName = 'US';
        }
        return $this->domain[$countryShortName];
    }
}