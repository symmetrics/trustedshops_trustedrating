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
	const EMAIL_WIDGET_LINK = 'https://www.trustedshops.com/bewertung/widget/img/';

	/**
	 * fixed part of the registration link
	 * 
	 * @var string
	 */
	const REGISTRATION_LINK = 'https://www.trustedshops.com/bewertung/anmeldung.html?';
	
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
	 private function _getRatingLinkData($type) 
	 {
	  	$optionValue = Mage::getStoreConfig('trustedrating/data/trustedrating_ratinglanguage');
		$link = Mage::helper('trustedrating')->getConfig($type, $optionValue);
		return $link;
	 }
	
	/**
	 * returns true, if the current language is choosen in the trusted rating config
	 * 
	 * @return boolean
	 */
	public function checkLocaleData() 
	{
		$storeId = Mage::app()->getStore()->getId();
		$countryCode = substr(Mage::getStoreConfig('general/locale/code', $storeId),0,2);

		if (Mage::getStoreConfig('trustedrating/data/trustedrating_ratinglanguage') == $countryCode) {
			return true;
		}
		return false;
	}
	
	/**
     * returns the rating link
	 *
	 * @return string
     */
	public function getRatingLink()
	{
		return $this->_getRatingLinkData('overwiewlanguagelink');
	}
	
	/**
	 * returns the email rating link
	 *
	 * @return string
     */
	public function getEmailRatingLink()
	{
		return $this->_getRatingLinkData('ratinglanguagelink');
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
	private function _cacheImageData($type, $tsId = null) 
	{
		$ioObject = new Varien_Io_File();
		
		if ($type == 'emailWidget') {
			$ioObject->open();
			$result = $ioObject->read(self::EMAIL_WIDGET_LINK);
			$ioObject->write(self::IMAGE_LOCAL_PATH . 'bewerten_de.gif', $result);
			$ioObject->close();
			Mage::app()->saveCache(self::IMAGE_LOCAL_PATH . 'bewerten_de.gif', self::EMAIL_CACHEID, array(), 1 ); 
		}
		else {
			$ioObject->open();
			$result = $ioObject->read(self::WIDGET_LINK . $tsId . '.gif');
		    $ioObject->write(self::IMAGE_LOCAL_PATH . $tsId . '.gif', $result);
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
		$this->_cacheImageData('emailWidget');
	}
	
	/**
	 * caches the widget image 
	 *
	 * @return void
	 */
	public function cacheImage($tsId)
	{
		$this->_cacheImageData('mainWidget', $tsId);
	}
	
	/**
	 * returns Registration Link
	 * 
	 * @return string
	 */
	 public function getRegistrationLink() 
	 {
		$link = self::REGISTRATION_LINK;
		$link.= 'partnerPackage='.Mage::helper('trustedrating')->getConfig('soapapi', 'partnerpackage');

		/*if symmetrics_impressum is installed, get Data from here*/
		if($data = Mage::getStoreConfig('general/impressum')) {
			$params = array(
				'company' => $data['company1'],
				'website' => $data['web'],
				'street' => $data['street'],
				'zip' => $data['zip'],
				'city' => $data['city'],
				'buyerEmail' => $data['email'],
			);

			foreach ($params as $key => $param) {
				if ($param) {
					$link .= '&' . $key . '=' . urlencode($param);
				}
			}
		}

		return $link;
	 }
}