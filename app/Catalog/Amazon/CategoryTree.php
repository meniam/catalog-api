<?php

namespace Catalog\Amazon;

use Catalog\Amazon\CategoryTree\IncorrectResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class CategoryTree
{
    private $request;
    private $callback;
    private $serializer;

    public function __construct(Request $request, $country, $callback)
    {
        $encoders = array(new XmlEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $this->serializer = new Serializer($normalizers, $encoders);
        $this->request = $request;
        $this->country = $country;
        $this->callback = $callback;
    }

    public function getTree($id, $parent = null)
    {
        $response = $this->getResponse($id);
        if (isset($response['BrowseNodes']['Request']['Errors'])) {
            throw new IncorrectResponse($response['BrowseNodes']['Request']['Errors']['Error']['Message']);
        }
        if (!isset($response['BrowseNodes'])) {
            return;
        }
        $this->browseNode($response['BrowseNodes'], $parent);
    }

    private function getResponse($id)
    {
        $params = [
            'Service' => 'AWSECommerceService',
            'Operation' => 'BrowseNodeLookup',
            'BrowseNodeId' => $id
        ];
        $data = $this->request->performFailsafe($this->country, $params);
        return $this->serializer->decode($data->getContent(), 'xml');
    }

    private function browseNode($responsePart, $parent)
    {
        if (!isset($responsePart['BrowseNode'])) {
            return;
        }
        $node = $responsePart['BrowseNode'];
        $category = $this->parseNode($node);
        $category['parent'] = $parent;
        call_user_func($this->callback, $category);
        if (isset($node['Children']['BrowseNode'])) {
            $children = $node['Children']['BrowseNode'];
            if (isset($children['BrowseNodeId'])) {
                $children = [$children];
            }
            foreach ($children as $child) {
                $childCategory = $this->parseNode($child);
                $childCategory['parent'] = $category['id'];
                $this->getTree($childCategory['id'], $category['id']);
            }
        }
    }

    private function parseNode($node)
    {
        if (!isset($node['BrowseNodeId'])) {
            throw new IncorrectResponse('BrowseNodeId not found in ' . var_export($node));
        }
        if (!isset($node['Name'])) {
            throw new IncorrectResponse('Name not found');
        }
        $result = ['id' => $node['BrowseNodeId'], 'name' => $node['Name'], 'country' => $this->country];
        if (isset($node['IsCategoryRoot'])) {
            $result['is_root'] = (bool) $node['IsCategoryRoot'];
        }
        return $result;
    }

}
