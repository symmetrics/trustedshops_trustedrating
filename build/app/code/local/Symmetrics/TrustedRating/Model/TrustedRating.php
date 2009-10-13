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
class Symmetrics_TrustedRating_Model_TrustedRating extends Mage_Core_Model_Abstract
{
	const IMAGELINK = 'https://qa.trustedshops.com/bewertung/widget/widgets/';
	const IMAGELOCALPATH = 'skin/frontend/default/default/images/trustedrating/';
	const CACHEID = 'trustedratingimage';
	
	public function getTsId() 
	{	
		return Mage::getStoreConfig('trustedrating/data/trustedrating_id');
	}
	
	public function getIsActive()
	{
		return Mage::getStoreConfig('trustedrating/status/trustedrating_active');
	}
	
	public function getRatingLink()
	{
		$optionValue =  Mage::getStoreConfig('trustedrating/data/trustedrating_ratinglanguage');
		$link = Mage::helper('trustedrating')->getConfig('ratinglanguagelink', $optionValue);
		return $link;
	}
	
	public function getImage()
	{
		$cacheTags = array();
		$tsId = $this->getTsId();
		$ratingLink = "<a href='".$this->getRatingLink()."_".$tsId.".html'>";
		
		if (!Mage::app()->loadCache(self::CACHEID)) {
			$current = file_get_contents(self::IMAGELINK.$tsId.'.gif');
			file_put_contents(self::IMAGELOCALPATH.$tsId.'gif', $current);
			Mage::app()->saveCache(self::IMAGELOCALPATH.$tsId.'gif', self::CACHEID, $cacheTags, 1 ); //cache only 1 second
			Mage::log("widget neu gecached");
		}
	
		return $ratingLink."<img src='".Mage::getBaseUrl().self::IMAGELOCALPATH.$tsId.".gif'/></a>";
	}
}