<?php
/**
 * Symmetrics_TrustedRating_Model_TrustedRating
 *
 * @category Symmetrics
 * @package Symmetrics_TrustedRating
 * @author symmetrics gmbh <info@symmetrics.de>, Siegfried Schmitz <ss@symmetrics.de>
 * @copyright symmetrics gmbh
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Symmetrics_TrustedRating_Model_Trustedrating extends Mage_Core_Model_Abstract
{
	/**
     * fixed part of the link for the rating-site for the widget
	 *
	 * @var string
     */
	const WIDGET_LINK = 'https://www.trustedshops.com/bewertung/widget/widgets/';
	
	/**
     * fixed part of the link for the rating-site for the email - widget
	 *
	 * @var string
     */
	const EMAIL_WIDGET_LINK = 'https://www.trustedshops.com/bewertung/widget/img/bewerten_de.gif';
	/**
     * fixed part of the widget path
	 *
	 * @var string
     */
	const IMAGELOCALPATH = 'media/';
	
	/**
     * the cacheid to cache the widget
	 *
	 * @var string
     */
	const CACHEID = 'trustedratingimage';
	
	/**
     * the cacheid to cache the email widget
	 *
	 * @var string
     */
	const EMAIL_CACHEID = 'trustedratingemailimage';
	
	/**
     * get the trusted rating id from store config
	 *
	 * @return string
     */
	public function getTsId() 
	{	
		return Mage::getStoreConfig('trustedrating/data/trustedrating_id');
	}
	
	/**
     * get the activity status from store config
	 *
	 * @return string
     */
	public function getIsActive()
	{
		return Mage::getStoreConfig('trustedrating/status/trustedrating_active');
	}
	
	/**
     * gets the selected language (for the rating - site) from the store config and returns
	 * the link for the widget, which stands in the module config for each language
	 *
	 * @return string
     */
	public function getRatingLink()
	{
		$optionValue =  Mage::getStoreConfig('trustedrating/data/trustedrating_ratinglanguage');
		$link = Mage::helper('trustedrating')->getConfig('ratinglanguagelink', $optionValue);
		return $link;
	}
	 /*
	 * gets the selected language (for the rating - site) from the store config and returns
	 * the link for the widget, which stands in the module config for each language
	 *
	 * @return string
     */
	public function getEmailRatingLink()
	{
		$optionValue =  Mage::getStoreConfig('trustedrating/data/trustedrating_ratinglanguage');
		$link = Mage::helper('trustedrating')->getConfig('ratingemaillanguagelink', $optionValue);
		return $link;	
	}
	
	/**
     * gets the link form the widget image from cache
	 *
	 * @return string
     */
	public function getImage()
	{
		$tsId = $this->getTsId();
		$ratingLink = "<a href='" . $this->getRatingLink() . "_" . $tsId . ".html'>";

		if (!Mage::app()->loadCache(self::CACHEID)) {
			$this->cacheImage($tsId);
		}
		
		return $ratingLink . "<img src='" . Mage::getBaseUrl() . self::IMAGELOCALPATH . $tsId . ".gif'/></a>";
	}
	
	/**
     * gets the link form the email widget image from cache
	 * @param int $orderId
	 * @param string $buyerEmail
	 * @return string
     */
	public function getEmailImage($orderId, $buyerEmail)
	{
		$tsId = $this->getTsId();
		$ratingLink = "<a href='" . $this->getEmailRatingLink() . "_" . $tsId . ".html&buyerEmail=".base64_encode($buyerEmail)."&shopOrderID=".base64_encode($orderId)."'>";
	
		if (!Mage::app()->loadCache(self::EMAIL_CACHEID)) {
			$this->cacheEmailImage();
		}
		
		return $ratingLink . "<img src='" . Mage::getBaseUrl() . self::IMAGELOCALPATH. "bewerten_de.gif'/></a>";
	}
	
	/**
	 * caches the email image 
	 *
	 * @return void
	 */
	public function cacheEmailImage()
	{
		$cacheTags = array();
		
		$current = file_get_contents(self::EMAIL_WIDGET_LINK);
		file_put_contents(self::IMAGELOCALPATH . 'bewerten_de.gif', $current);
		Mage::app()->saveCache(self::IMAGELOCALPATH . 'bewerten_de.gif', self::EMAIL_CACHEID, $cacheTags, 1 ); //for testing: cache only 1 second
		Mage::log("widget neu gecached");
	}
	
	/**
	 * caches the widget image 
	 *
	 * @return void
	 */
	public function cacheImage($tsId)
	{
		$cacheTags = array();
		
		$current = file_get_contents(self::WIDGET_LINK . $tsId . '.gif');
		file_put_contents(self::IMAGELOCALPATH . $tsId . '.gif', $current);
		Mage::app()->saveCache(self::IMAGELOCALPATH . $tsId . '.gif', self::CACHEID, $cacheTags, 1 ); //for testing: cache only 1 second
		Mage::log("widget neu gecached");
	}
}