<?php

namespace Catalog\Amazon;

use Catalog\Exception\ApiError;
use Catalog\Exception\AsinNotFound;
use Catalog\Exception\BadIdFormat;
use Catalog\Exception\DefaultImageNotFound;
use Catalog\Exception\InvalidUrl;
use Catalog\Exception\ProductNotFound;
use Catalog\Exception\ValueObject\NegativePrice;
use Catalog\ValueObject\Aspect;
use Catalog\ValueObject\Price;

class ProductCreator
{
    private $response;
    private $product;

    public function __construct($response)
    {
        $this->response = $response;
        $this->product = new Product();
    }

    public function build()
    {
        if (isset($this->response['Items']['Errors'])) {
            throw new ProductNotFound();
        }
        $item = $this->response['Items']['Item'];
        $this->assignId($item);
        $this->assignLink($item);
        $this->assignName($item);
        $this->assignParentId($item);
        $this->assignSalesRank($item);
        $this->assignDefaultImage($item);
        $this->assignImages($item);
        $this->assignPrice($item);
        $this->assignAttributes($item);
        $this->assignVariations($item);
        $this->assignFeatures($item);
        $this->assignDescription($item);
        $this->assignPackageDimensions($item);
        $this->assignItemDimensions($item);
        $this->product->freeze();
        return $this->product;
    }

    private function assignId($item)
    {
        if (!isset($item['ASIN'])) {
            throw new AsinNotFound();
        }
        $id = $item['ASIN'];
        $this->checkIdFormat($id);
        $this->product->originalId = $id;
    }

    private function assignLink($item)
    {
        if (isset($item['DetailPageURL'])) {
            $this->product->originalLink = $item['DetailPageURL'];
        }
    }

    private function assignName($item)
    {
        if (!isset($item['ItemAttributes']['Title'])) {
            throw new ApiError('item name not found');
        }
        $this->product->name = $item['ItemAttributes']['Title'];
    }

    private function assignParentId($item)
    {
        if (!isset($item['ParentASIN'])) {
            return;
        }
        $id = $item['ParentASIN'];
        $this->checkIdFormat($id);
        $this->product->parentId = $id;
    }

    private function checkIdFormat($id)
    {
        if (!preg_match('/^[A-Z\d]+$/', $id)) {
            throw new BadIdFormat();
        }
    }

    private function assignSalesRank($item)
    {
        if (isset($item['SalesRank'])) {
            $this->product->salesRank = (int)$item['SalesRank'];
        }
    }

    private function assignDefaultImage($item)
    {
        if (!isset($item['LargeImage']['URL'])) {
            throw new DefaultImageNotFound();
        }
        $url = $item['LargeImage']['URL'];
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrl('default image');
        }
        $this->product->defaultImage = $url;
    }

    private function assignImages($item)
    {
        if (!isset($item['ImageSets']['ImageSet'])) {
            return;
        }
        $images = [];
        foreach ($item['ImageSets']['ImageSet'] as $set) {
            if (isset($set['LargeImage']['URL'])) {
                $images[] = $set['LargeImage']['URL'];
            }
        }
        $this->product->imageList = $images;
    }

    private function assignPrice($item)
    {
        if (isset($item['Offers']['Offer']['OfferListing']['Price'])) {
            $offer = $item['Offers']['Offer']['OfferListing']['Price'];
            $this->product->price = new Price($offer['Amount'] / 100, $offer['CurrencyCode']);
        }
        $this->assignLowerestPrice($item);
        $this->assignHighestPrice($item);
        if (!$this->product->price) {
            $this->product->price = $this->product->minPrice;
        }
        if (!$this->product->price) {
            $this->product->price = $this->product->maxPrice;
        }
        if (!$this->product->price) {
            throw new NegativePrice('price equals to zero, negative or not exists');
        }
    }

    private function assignLowerestPrice($item)
    {
        if (isset($item['VariationSummary']['LowestPrice']['Amount'])) {
            $offer = $item['VariationSummary']['LowestPrice'];
            $this->product->minPrice = new Price($offer['Amount'] / 100, $offer['CurrencyCode']);
        }
    }

    private function assignHighestPrice($item)
    {
        if (isset($item['VariationSummary']['HighestPrice']['Amount'])) {
            $offer = $item['VariationSummary']['HighestPrice'];
            $this->product->maxPrice = new Price($offer['Amount'] / 100, $offer['CurrencyCode']);
        }
    }

    private function assignAttributes($item)
    {
        $rejectedAttributes = ['ListPrice', 'PackageDimensions', 'PackageQuantity', 'UPCList', 'EANList', 'Feature',
            'ItemDimensions', 'CatalogNumberList', 'Languages', 'Title'];
        foreach ($item['ItemAttributes'] as $attribute => $values) {
            if (!in_array($attribute, $rejectedAttributes)) {
                $aspects[] = new Aspect($attribute, (array)$values);
            }
        }
        $this->product->aspects = $aspects;
    }

    private function assignPackageDimensions($item)
    {
        if (isset($item['ItemAttributes']['PackageDimensions'])) {
            $dc = new DimensionsContainer($item['ItemAttributes']['PackageDimensions']);
            $this->product->packageDimensions = $dc->getTransformedData();
        }
    }

    private function assignItemDimensions($item)
    {
        if (isset($item['ItemAttributes']['ItemDimensions'])) {
            $dc = new DimensionsContainer($item['ItemAttributes']['ItemDimensions']);
            $this->product->itemDimensions = $dc->getTransformedData();
        }
    }

    private function assignFeatures($item)
    {
        if (isset($item['ItemAttributes']['Feature'])) {
            $this->product->features = $item['ItemAttributes']['Feature'];
        }
    }

    private function assignDescription($item)
    {
        if (isset($item['EditorialReviews']['EditorialReview']['Content'])) {
            $this->product->description = $item['EditorialReviews']['EditorialReview']['Content'];
        }
    }

    private function assignVariations($item)
    {
        if (!isset($item['Variations']['Item']['VariationAttributes'])) {
            return;
        }
        $variations = [];
        foreach ($item['Variations']['Item']['VariationAttributes'] as $subitem) {
            $variations[] = (new VariationCreator($subitem))->create();
        }
        $this->product->variations = $variations;
    }
}