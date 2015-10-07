<?php
namespace Catalog\EbayAPI;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Catalog\Exception\ApiError;

class Api
{
    const SHOPPING_URL = 'http://open.api.ebay.com/shopping?';

    const TRADING_URL = 'https://api.ebay.com/ws/api.dll';

    const FINDING_URL = 'http://svcs.ebay.com/services/search/FindingService/v1?';

    /**
     * @var \Symfony\Component\Serializer\Serializer
     */
    protected $serializer;

    protected  $ebayKeys;

    protected $errorMessagesList = array(
        'critical' => array(),
        'warning' => array()
    );

    private $errorNotCritical = array('1.15', '1.16', '1.17', '1.24', '1.29',
        '1.30', '10.14', '10.64', '10.65',
        '10.77', '10.78', '10.79', '10.80',
        '10.86', '10.91', '10.92', '10.93',
        '10.94', '10.96');

    public function __construct()
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $this->serializer = new Serializer($normalizers, $encoders);
        $this->ebayKeys = $this->getEbayConfig();
    }

    protected function apiErrors($response, $id)
    {
        if (!isset($response['Errors'])) {
            return;
        }
        if (!empty($response['Errors']['ShortMessage'])) {
            if (array_search($response['Errors']['ErrorCode'], $this->errorNotCritical)) {
                $this->errorMessagesList['warning'][] = $response['Errors']['ShortMessage'];
            } else {
                $this->errorMessagesList['critical'][] = $response['Errors']['ShortMessage'];
            }

        } else {
            foreach ($response['Errors'] as $error) {
                if (array_search($error['ErrorCode'], $this->errorNotCritical)) {
                    $this->errorMessagesList['warning'][] = $error['ShortMessage'];
                    continue;
                }
                $this->errorMessagesList['critical'][] = $error['ShortMessage'];
            }
        }
        if (!empty($this->errorMessagesList['critical'])) {
            throw new ApiError(implode('. ', $this->errorMessagesList['critical']) . "Product id {$id}");
        }
    }

}