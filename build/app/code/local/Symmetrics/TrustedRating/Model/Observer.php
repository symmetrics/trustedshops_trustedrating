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
		$storeId = $observer->getStore();
		Mage::log($storeId);
		$soapUrl = Mage::helper('trustedrating')->getConfig('soapapi', 'url');
		$sendData = $this->_getSendData($storeId);
		$returnValue = $this->_callTrustedShopsApi($sendData, $soapUrl);
		
		Mage::getSingleton('core/session')->addNotice('returnValue: ' . $returnValue);
	}
	
	/**
     * collect the data for sending to trusted rating
	 *
	 * @return array
     */	
	private function _getSendData($storeId) 
	{
		Mage::log($storeId);

		$sendData = array();
		
		$sendData['tsId'] = Mage::getStoreConfig('trustedrating/data/trustedrating_id', $storeId);
		$sendData['activation'] = Mage::getStoreConfig('trustedrating/status/trustedrating_active', $storeId);
		$sendData['wsUser'] = Mage::helper('trustedrating')->getConfig('soapapi', 'wsuser');
		$sendData['wsPassword'] = Mage::helper('trustedrating')->getConfig('soapapi', 'wspassword');
		$sendData['partnerPackage'] = Mage::helper('trustedrating')->getConfig('soapapi', 'partnerpackage');
		
		return $sendData;	
	}
	
	/**
     * calling the soap api from trusted rating
	 * 
	 * @param array $send_data
	 * @param array $soap_url
	 * @return string
     */
	private function _callTrustedShopsApi($sendData, $soapUrl) 
	{
		$returnValue = 'SOAP_ERROR';
		try {
			$client = new SoapClient($soapUrl);
			$returnValue = $client->updateRatingWidgetState(
				$sendData['tsId'],
				$sendData['activation'],
				$sendData['wsUser'],
				$sendData['wsPassword'],
				$sendData['partnerPackage']
			);
				
		} catch (SoapFault $fault) {
			$errorText = 'SOAP Fault: (faultcode: ' . $fault->faultcode . ', faultstring: ' . $fault->faultstring . ')';
			Mage::log($errorText);
		}
		
		return $returnValue;
	}
}