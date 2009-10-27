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
 		if ($ratingLinkData = $this->getImageData(true)) {
			return '<a href="' . $ratingLinkData['ratingLink'] . '_' . $ratingLinkData['tsId'] . '.html&buyerEmail=' . base64_encode($ratingLinkData['buyerEmail']) . '&shopOrderID=' . base64_encode($ratingLinkData['orderId']) . '">' . '<img src="' . Mage::getBaseUrl() . $ratingLinkData['imageLocalPath'] . 'bewerten_de.gif"/></a>';
		}
    	else {
    		return null;
    	}
    }
}