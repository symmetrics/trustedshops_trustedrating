<?php
/**
 * Symmetrics_TrustedRating_Block_Rateus
 *
 * @category Symmetrics
 * @package Symmetrics_TrustedRating
 * @author symmetrics gmbh <info@symmetrics.de>, Siegfried Schmitz <ss@symmetrics.de>
 * @copyright symmetrics gmbh
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Symmetrics_TrustedRating_Block_Email_Widget extends Symmetrics_TrustedRating_Block_Widget_Abstract
{
    /**
     * returns the email widget 
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($data = $this->getDataForWidget('EMAIL')) {
            $buyerEmail = base64_encode($data['buyerEmail']);
            $baseUrl = Mage::getBaseUrl();
            if(strpos($baseUrl,'index.php')) {
                $baseUrl = substr(Mage::getBaseUrl(),0,strrpos(Mage::getBaseUrl(),'index.php'));
            }
            $orderId = base64_encode($data['orderId']);
            $link = '<a href="' . $data['ratingLink'] . '_' . $data['tsId'] . '.html';
            $params = '&buyerEmail=' . $buyerEmail . '&shopOrderID=' . $orderId . '">';
            $widget = '<img src="' . $baseUrl . $data['imageLocalPath'] . $data['widgetName'] . '"/></a>';
            return $link . $params . $widget;
        } else {
            return null;
        }
    }
}