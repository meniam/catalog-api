<?php

namespace Catalog\Taobao;

class Conditions
{
    private $params;

    public function setCategoryId($id)
    {
        $this->params['cat'] = $id;
    }

    public function setPage($page)
    {
        $this->params['page_no'] = $page;
    }

    public function setItemsPerPage($num)
    {
        $this->params['page_size'] = $num;
    }

    public function setKeywords($query)
    {
        $this->params['q'] = $query;
    }

    public function getParams()
    {
        return $this->params;
    }
}
