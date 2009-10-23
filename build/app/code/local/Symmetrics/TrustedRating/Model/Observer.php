<?php
/**
 * Symmetrics_TrustedRating_Model_Observer
 *
 * @category Symmetrics
 * @package Symmetrics_TrustedRating
 * @author symmetrics gmbh <info@symmetrics.de>, Siegfried Schmitz <ss@symmetrics.de>
 * @copyright symmetrics gmbh
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Symmetrics_TrustedRating_Model_Observer
{
	/**
     * change the activity status (active, inactive) by sending an api call to trusted rating
	 *
     * @param Varien_Event_Observer $observer
	 * @return void
     */
	public function changeTrustedRatingStatus($observer) 
	{	
		$soapUrl = Mage::getStoreConfig('trustedrating/data/trustedrating_soapurl');
		$sendData = $this->getSendData();
		
		if (strlen(trim($soapUrl)) == 0) {
			//use default value from config if user had cleared the field in the configuration
			$soapUrl = Mage::helper('trustedrating')->getConfig('soapapi','url');
			Mage::getModel('core/config')->saveConfig('trustedrating/data/trustedrating_soapurl', $soapUrl); 
		}
		$returnValue = $this->callTrustedShopsApi($sendData, $soapUrl);
		Mage::getSingleton('core/session')->addNotice('returnValue: ' . $returnValue);
	}
	
	/**
     * collect the data for sending to trusted rating
	 *
	 * @return array
     */	
	private function getSendData() 
	{
		$sendData = array();
		$sendData['tsId'] = Mage::getStoreConfig('trustedrating/data/trustedrating_id');
		$sendData['activation'] = Mage::getStoreConfig('trustedrating/status/trustedrating_active');
		$sendData['wsUser'] = Mage::getStoreConfig('trustedrating/data/trustedrating_user');
		$sendData['wsPassword'] = Mage::getStoreConfig('trustedrating/data/trustedrating_password');
		$sendData['partnerPackage'] = 'partnerPackage';
		
		return $sendData;	
	}
	
	/**
     * calling the soap api from trusted rating
	 * 
	 * @param array $send_data
	 * @param array $soap_url
	 * @return string
     */
	private function callTrustedShopsApi($sendData, $soap_url) 
	{
		try {
			$client = new SoapClient($soap_url);
			$returnValue = 'SOAP_ERROR';
			
			$returnValue = $client->updateRatingWidgetState(
				$sendData['tsId'],
				$sendData['activation'],
				$sendData['wsUser'],
				$sendData['wsPassword'],
				$sendData['partnerPackage']
			);
				
		} catch (SoapFault $fault) {
			$errorText = "SOAP Fault: (faultcode: {$fault->faultcode}, faultstring:
			{$fault->faultstring})";
			Mage::log($errorText);
		}
		
		return $returnValue;
	}
}