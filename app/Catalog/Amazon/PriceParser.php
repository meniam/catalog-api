<?php

namespace Catalog\Amazon;

class PriceParser
{
    private $pq;

    public function __construct($url)
    {
        $this->pq = phpQuery::newDocumentFileHTML($url);
    }

    public function getOffers()
    {
        $links = [];
        foreach ($this->pq->find('ul.a-pagination li a') as $pagerElem) {
            if (is_int($pagerElem['textContent'])) {
                continue;
            }
            $link = $pagerElem->attributes->getNamedItem('href')->value;
            if ($link == '#') {
                continue;
            }
            $links[] = $link;
        }
        $results = $this->getOffersFromPage();
        foreach ($links as $link) {
            $this->pq = phpQuery::newDocumentFileHTML($link);
            $results = array_merge($results, $this->getOffersFromPage());
        }
        return $results;
    }

    private function getOffersFromPage()
    {
        $prices = $this->getProperties('.olpOffer .olpOfferPrice');
        $conditions = $this->getProperties('.olpOffer .olpCondition');
        $sellers = $this->getProperties('.olpOffer .olpSellerName a');
        $result = [];
        for ($i = 0; $i < count($prices); $i++) {
            $result[] = ['price' => $prices[$i], 'condition' => $conditions[$i], 'seller' => $sellers[$i]];
        }
        return $result;
    }

    private function getProperties($selector)
    {
        $values = [];
        foreach ($this->pq->find($selector) as $node) {
            $values[] = trim($node->nodeValue);
        }
        return $values;
    }
}