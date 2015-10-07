<?php
namespace Catalog\Product;


class Seller
{
    private $name;

    private $feedbackScore;

    private $positiveFeedbackPercent;

    private $isMotorsDealer;

    private $feedbackRatingStar;

    private $storeUrl;

    public function __construct($sellerInfo)
    {
        $this->name = $sellerInfo['UserID'];
        $this->feedbackScore = (int)$sellerInfo['FeedbackScore'];
        $this->positiveFeedbackPercent = (float)$sellerInfo['PositiveFeedbackPercent'];
        $this->isMotorsDealer = isset($sellerInfo['MotorsDealer'])
            ?  (boolean)$sellerInfo['MotorsDealer'] : false;
        $this->feedbackRatingStar = $sellerInfo['FeedbackRatingStar'];
        $this->storeUrl = !empty($sellerInfo['SellerInfo']['StoreURL']) ? $sellerInfo['SellerInfo']['StoreURL'] : false;
    }

    /**
     * @return float
     */
    public function getPositiveFeedbackPercent()
    {
        return $this->positiveFeedbackPercent;
    }

    /**
     * @return string
     */
    public function getFeedbackRatingStar()
    {
        return $this->feedbackRatingStar;
    }

    /**
     * @return int
     */
    public function getFeedbackScore()
    {
        return $this->feedbackScore;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function isMotorsDealer()
    {
        return $this->isMotorsDealer;
    }

    /**
     * @return boolean|string
     */
    public function getStoreUrl()
    {
        return $this->storeUrl;
    }  

}