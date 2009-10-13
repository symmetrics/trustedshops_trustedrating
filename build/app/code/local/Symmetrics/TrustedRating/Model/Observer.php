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
	public function changeTrustedRatingStatus($observer) 
	{	
		$soap_url = Mage::getStoreConfig('trustedrating/data/trustedrating_soapurl');
		$send_data = $this->getSendData();
		
		if($soap_url == '') {
			//use default from config if user had cleared the field in the configuration
			$soap_url = Mage::helper('trustedrating')->getConfig('soapapi','url');
			Mage::getModel('core/config')->saveConfig('trustedrating/data/trustedrating_soapurl', $soap_url); 
		}
		
		$returnValue = $this->callTrustedShopsApi($send_data,$soap_url);
		Mage::getSingleton('core/session')->addNotice('returnValue: '.$returnValue);
	}
	
	private function callTrustedShopsApi($send_data,$soap_url) 
	{
		try {
			$client = new SoapClient($soap_url);
			$returnValue = 'SOAP_ERROR';
			
			$returnValue = $client->updateRatingWidgetState($send_data['tsId'], $send_data['activation'], $send_data['wsUser'], $send_data['wsPassword'], $send_data['partnerPackage'] );
				
		} catch (SoapFault $fault) {
			$errorText = "SOAP Fault: (faultcode: {$fault->faultcode}, faultstring:
			{$fault->faultstring})";
			Mage::log($errorText);
		}
		
		return $returnValue;
	}
	
	private function getSendData() 
	{
		$send_data = array();
		$send_data['tsId'] = Mage::getStoreConfig('trustedrating/data/trustedrating_id');
		$send_data['activation'] = Mage::getStoreConfig('trustedrating/status/trustedrating_active');
		$send_data['wsUser'] = Mage::getStoreConfig('trustedrating/data/trustedrating_user');
		$send_data['wsPassword'] = Mage::getStoreConfig('trustedrating/data/trustedrating_password');
		$send_data['partnerPackage'] = 'partnerPackage';
		
		return $send_data;	
	}
}