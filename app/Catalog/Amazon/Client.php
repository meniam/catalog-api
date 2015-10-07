<?php

namespace Catalog\Amazon;

use Catalog\Exception\ApiError;
use Catalog\Exception\RequestThrottled;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class Client
{
    private $keys;
    private $httpClient;
    private $serializer;

    public function __construct(\Buzz\Browser $httpClient, array $keys)
    {
        $this->keys = $keys;
        $this->httpClient = $httpClient;
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new GetSetMethodNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function getSubcategories($categoryId, $country, $callback)
    {
        $request = new Request($this->httpClient, $this->keys);
        return (new CategoryTree($request, $country, $callback))->getTree($categoryId);
    }

    public function getProduct($productId, $country)
    {
        $response = $this->call($country, [
            'Operation' => 'ItemLookup',
            'ItemId' => $productId,
            'IdType' => 'ASIN',
            'ResponseGroup' => 'Large,Variations,OfferFull',
            'Condition' => 'All'
        ]);
        return (new ProductCreator($response))->build();
    }

    public function getProductList(SearchCondition $condition, $country)
    {
        $response = $this->call($country, array_merge([
            'Operation' => 'ItemSearch',
            'SearchIndex' => 'Video',
            'ResponseGroup' => 'ItemIds'
        ], $condition->toArray()));
        if (!$response['Items']) {
            throw new ApiError('Bad response');
        }
        if (!$response['Items']['Item']) {
            throw new ApiError('No products');
        }
        return new ProductList($response);
    }

    private function call($country, $params)
    {
        $request = new Request($this->httpClient, $this->keys);
        $response = $request->perform($country, $params);
        $resp = $this->serializer->decode($response->getContent(), 'xml');
        if (isset($resp['Error'])) {
            $this->handleError($resp['Error']['Code']);
        }
        return $resp;
    }

    private function handleError($code)
    {
        if ($code == 'RequestThrottled') {
            throw new RequestThrottled();
        }
    }
}
