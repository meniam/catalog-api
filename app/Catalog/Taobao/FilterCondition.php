<?php

namespace Catalog\Taobao;

use Catalog\Exception\BadRequest;

class FilterCondition
{
    private $params;

    public function setKeywords($keywords)
    {
        $this->params['q'] = $keywords;
    }

    public function setCategoryId($cid)
    {
        $this->params['cat'] = $cid;
    }

    public function setPage($pageNumber)
    {
        $this->params['page_no'] = $pageNumber;
    }

    public function setItemsPerPage($num)
    {
        $this->params['page_size'] = $num;
    }

    public function sort($type)
    {
        $allowedSorts = [
            'BEST_MATCH' => 's',
            'PRICE_ASC' => 'p',
            'PRICE_DESC' => 'pd',
            'NAME' => 'pt'
        ];
        if (!isset($allowedSorts[$type])) {
            throw new BadRequest('unknown sort type');
        }
        $this->params['sort'] = $allowedSorts[$type];
    }

    public function setAspects($aspects)
    {
        $pidvids = [];
        foreach ($aspects as $k=>$v) {
            $pidvids[] = "$k:$v";
        }
        $this->params['props'] = implode(';', $pidvids);
    }

    public function getParams()
    {
        return $this->params;
    }
}
