<?php

namespace Catalog\EbayItem;

use Catalog\EbayProductCreator;
use Catalog\Exception\EmptyParam;
use Catalog\ValueObject\NullPrice;
use Catalog\ValueObject\Price;

class Single extends EbayProductCreator
{
    public function parse()
    {
        $this->assignShippingCostSummary();
        $this->assignIsAuction();
        $this->assignOriginalLink();
        $this->assignExpirationTime();
        $this->assignMinimumToBid();
        return parent::parse();
    }

    private function assignExpirationTime()
    {
        if (!empty($this->response['EndTime'])) {
            $endTime = $this->response['EndTime'];
        } else {
            throw new EmptyParam('Product expiration time is empty or not exist');
        }
        $this->product->expireAt = new \DateTime($endTime);
    }

    private function assignShippingCostSummary()
    {
        if (isset($this->response['ShippingCostSummary'])) {
            $this->product->shippingCost = $this->validation->getShippingCostSummaryForSingle($this->response['ShippingCostSummary']);
        } else {
            $this->product->shippingCost = $this->validation->getShippingCostSummaryForSingle();
        }
    }

    private function assignOriginalLink()
    {
        if (!empty($this->response['ViewItemURLForNaturalSearch'])) {
            $originalLing = $this->response['ViewItemURLForNaturalSearch'];
        } else {
            throw new EmptyParam('OriginalLink is not int or not exist');
        }
        $this->product->originalLink = (string) $originalLing;
    }

    private function assignIsAuction()
    {
        $this->product->isAuction = (boolean) $this->validation->isAuctionSingle($this->response);
    }

    private function assignMinimumToBid()
    {
        if (isset($this->response['MinimumToBid']['@currencyID'])) {
            $this->product->minimumToBid = new Price($this->response['MinimumToBid']['#'],
                $this->response['MinimumToBid']['@currencyID']);
        } else {
            $this->product->minimumToBid = new NullPrice();
        }

    }

}