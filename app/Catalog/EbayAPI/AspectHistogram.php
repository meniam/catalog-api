<?php

namespace Catalog\EbayAPI;

use Catalog\Exception\EmptyParam;
use Catalog\ValueObject\Aspect;

class AspectHistogram
{
    private $response;
    private $domainName;
    private $domainDisplayName;
    private $aspects = [];

    public function __construct($response)
    {
        $this->response = $response;
        if ($this->hasDomain()) {
            $this->assignDomainName();
            $this->assignDomainDisplayName();
        }
        $this->assignAspects();
    }

    public function hasDomain()
    {
        return isset($this->response['domainName']);
    }

    public function assignDomainName()
    {
        $this->domainName = $this->response['domainName'];
    }

    public function assignDomainDisplayName()
    {
        if (isset($this->response['domainDisplayName'])) {
            $this->domainDisplayName = $this->response['domainDisplayName'];
        }
    }

    public function assignAspects()
    {
        if (!isset($this->response['aspect'])) {
            return;
        }
        if (isset($this->response['aspect']['@name'])) {
            $this->response['aspect'] = [$this->response['aspect']];
        }
        foreach ($this->response['aspect'] as $aspect) {
            if (!isset($aspect['@name'])) {
                continue;
            }
            $values = [];
            if (isset($aspect['valueHistogram']['@valueName'])) {
                $aspect['valueHistogram'] = [$aspect['valueHistogram']];
            }
            foreach ($aspect['valueHistogram'] as $item) {
                if (!isset($item['@valueName']) || !isset($item['count'])) {
                    continue;
                }
                $values[$item['@valueName']] = intval($item['count']);
            }
            $this->aspects[] = new Aspect($aspect['@name'], $values);
        }
    }

    public function getDomainName()
    {
        return $this->domainName;
    }

    public function getDomainDisplayName()
    {
        return $this->domainDisplayName;
    }

    public function getAspects()
    {
        return $this->aspects;
    }
}