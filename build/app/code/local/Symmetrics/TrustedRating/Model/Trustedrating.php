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
	 * fixed part of the registration link
	 * 
	 * @var string
	 */
	const REGISTRATION_LINK = 'https://www.trustedshops.com/bewertung/anmeldung.html?partnerPackage=partnerPackage';
	
	/**
     * fixed part of the widget path
	 *
	 * @var string
     */
	const IMAGE_LOCAL_PATH = 'media/';
	
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
	 * @param string $type
	 * @return string
	 */
	 private function getRatingLinkData($type) 
	 {
	  	$optionValue =  Mage::getStoreConfig('trustedrating/data/trustedrating_ratinglanguage');
		$link = Mage::helper('trustedrating')->getConfig($type, $optionValue);
		return $link;
	 }
	
	/**
     * returns the rating link
	 *
	 * @return string
     */
	public function getRatingLink()
	{
		return $this->getRatingLinkData('ratinglanguagelink');
	}
	
	/**
	 * returns the email rating link
	 *
	 * @return string
     */
	public function getEmailRatingLink()
	{
		return $this->getRatingLinkData('ratingemaillanguagelink');
	}
	
	/**
     * gets the link data for the widget image from cache
	 *
	 * @return array
     */
	public function getRatingWidgetData()
	{
		$tsId = $this->getTsId();

		if (!Mage::app()->loadCache(self::CACHEID)) {
			$this->cacheImage($tsId);
		}

		return array(
			'tsId' => $tsId,
			'ratingLink' => $this->getRatingLink(),
			'imageLocalPath' => self::IMAGE_LOCAL_PATH
		);
	}
	
	/**
     * gets the link data for the email widget image from cache
     *
	 * @return array
     */
	public function getEmailWidgetData()
	{
		$tsId = $this->getTsId();
		$orderId = Mage::getSingleton('checkout/type_onepage')->getCheckout()->getLastOrderId();
		$order = Mage::getModel('sales/order')->load($orderId);
		$buyerEmail = $order->getData('customer_email');
			
		if (!Mage::app()->loadCache(self::EMAIL_CACHEID)) {
			$this->cacheEmailImage();
		}
		
		return array(
			'tsId' => $tsId,
			'ratingLink' => $this->getEmailRatingLink(),
			'imageLocalPath' => self::IMAGE_LOCAL_PATH,
			'orderId' => $orderId,
			'buyerEmail' => $buyerEmail
		);
	}
	
	/**
	 * caches the widget images
	 * 
	 * @param string $type
	 * @param string $tsId
	 * @return void
	 */
	private function cacheImageData($type, $tsId = null) 
	{
		$ioObject = new Varien_Io_File();
		
		if ($type == 'emailWidget') {
			$ioObject->open();
			$ioObject->read(self::EMAIL_WIDGET_LINK, self::IMAGE_LOCAL_PATH . 'bewerten_de.gif');
			$ioObject->close();
			Mage::app()->saveCache(self::IMAGE_LOCAL_PATH . 'bewerten_de.gif', self::EMAIL_CACHEID, array(), 1 ); 
		}
		else {
			$ioObject->open();
			$result = $ioObject->read(self::WIDGET_LINK . $tsId . '.gif', self::IMAGE_LOCAL_PATH . $tsId . '.gif');
			$ioObject->close();
			Mage::app()->saveCache(self::IMAGE_LOCAL_PATH . $tsId . '.gif', self::CACHEID, array(), 1 );
		}
	}
	
	/**
	 * caches the email image 
	 *
	 * @return void
	 */
	public function cacheEmailImage()
	{
		$this->cacheImageData('emailWidget');
	}
	
	/**
	 * caches the widget image 
	 *
	 * @return void
	 */
	public function cacheImage($tsId)
	{
		$this->cacheImageData('mainWidget', $tsId);
	}
	
	/**
	 * returns Registration Link
	 * 
	 * @return string
	 */
	 public function getRegistrationLink() 
	 {
		$link = self::REGISTRATION_LINK;
		
	  	$params = array(
			'company' => Mage::getStoreConfig('trustedrating/data/trustedrating_company'),
			'legalForm' => Mage::getStoreConfig('trustedrating/data/trustedrating_legalform'),
			'website' => Mage::getStoreConfig('trustedrating/data/trustedrating_website'),
			'firstName' => Mage::getStoreConfig('trustedrating/data/trustedrating_firstname'),
			'lastName' => Mage::getStoreConfig('trustedrating/data/trustedrating_lastname'),
			'street' => Mage::getStoreConfig('trustedrating/data/trustedrating_street'),
			'streetNumber' => Mage::getStoreConfig('trustedrating/data/trustedrating_hn'),
			'zip' => Mage::getStoreConfig('trustedrating/data/trustedrating_zip'),
			'city' => Mage::getStoreConfig('trustedrating/data/trustedrating_city'),
			'buyerEmail' => Mage::getStoreConfig('trustedrating/data/trustedrating_mail'),
			'country' => Mage::getStoreConfig('trustedrating/data/trustedrating_country'),
			'language' => Mage::getStoreConfig('trustedrating/data/trustedrating_language'),
		);

		foreach ($params as $key => $param) {
			if ($param) {
				$link .=  '&' . $key . '=' . urlencode($param);
			}
		}
		
		return $link;
	 }
}