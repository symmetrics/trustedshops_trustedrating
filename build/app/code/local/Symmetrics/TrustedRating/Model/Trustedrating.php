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
	const WIDGET_LINK = 'https://qa.trustedshops.com/bewertung/widget/widgets/';
	
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
			$this->_cacheImage($tsId);
		}
		
		return $ratingLink . "<img src='" . Mage::getBaseUrl() . self::IMAGELOCALPATH . $tsId . ".gif'/></a>";
	}
	
	private function _cacheImage($tsId) 
	{
		$cacheTags = array();
		
		$current = file_get_contents(self::WIDGET_LINK . $tsId . '.gif');
		file_put_contents(self::IMAGELOCALPATH . $tsId . '.gif', $current);
		Mage::app()->saveCache(self::IMAGELOCALPATH . $tsId . '.gif', self::CACHEID, $cacheTags, 1 ); //for testing: cache only 1 second
		Mage::log("widget neu gecached");
	}
}