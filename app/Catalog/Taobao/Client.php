<?php

namespace Catalog\Taobao;

class Client
{
    public function getProduct($id)
    {
        $url = $this->getUrl([
            'method' => 'taobao.item.get',
            'fields' => 'detail_url,num_iid,title,nick,type,cid,seller_cids,props,input_pids,' .
                    'input_str,desc,pic_url,num,valid_thru,list_time,delist_time,stuff_status,' .
                    'location,price,post_fee,express_fee,ems_fee,has_discount,freight_payer,' .
                    'has_invoice,has_warranty,has_showcase,modified,increment,approve_status,' .
                    'postage_id,product_id,auction_point,property_alias,item_img,prop_img,sku,' .
                    'video,outer_id,is_virtual',
            'num_iid' => $id
        ]);
        return new Product($this->getResponse($url));
    }

    public function getSubcategories($categoryId)
    {
        $url = $this->getUrl([
            'method' => 'taobao.itemcats.get',
            'fields' => 'cid,parent_cid,name,is_parent',
            'parent_cid' => $categoryId
        ]);
        return new CategoryList($this->getResponse($url));
    }

    public function getHistogram($categoryId)
    {
        $url = $this->getUrl([
            'method' => 'taobao.itemprops.get',
            'fields' => 'pid,name,must,multi,prop_values',
            'cid' => $categoryId
        ]);
        return new Histogram($this->getResponse($url));
    }

    public function find(Conditions $filterCondition)
    {
        $url = $this->getUrl(array_merge([
            'method' => 'tmall.items.extend.search',
            'fields' => 'product_id,name,pic_url,cid,props,price,tsc'
        ], $filterCondition->getParams()));
        return new ProductList($this->getResponse($url));
    }

    private function getUrl($params)
    {
        $key = Config::getKey();
        $sig = new Signaturer($key['public'], $key['private']);
        return $sig->getUrl($params);
    }

    private function getResponse($url)
    {
        return (new Request(Config::getProxy()))->perform($url);
    }
}