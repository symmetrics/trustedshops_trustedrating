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
	public function trustedshopsapicall($observer) {
		//Mage::getSingleton('core/session')->addNotice("Status: ".Mage::getStoreConfig('trustedrating/status/trustedrating_active'));
		
		$status = Mage::getStoreConfig('trustedrating/status/trustedrating_active');
		$soap_url = Mage::getStoreConfig('trustedrating/data/trustedrating_soapurl');
		
		if($soap_url == '') {
			//use default from config if user had cleared the field in the configuration
			$soap_url = Mage::helper('trustedrating')->getConfig('soapapi','url');
			Mage::getModel('core/config')->saveConfig('trustedrating/data/trustedrating_soapurl', $soap_url); 
		}
		Mage::log("soap_url: ".$soap_url);
		$send_data = array();
		$send_data['tsId'] = Mage::getStoreConfig('trustedrating/data/trustedrating_id');
		$send_data['activation'] = $status;
		$send_data['wsUser'] = Mage::getStoreConfig('trustedrating/data/trustedrating_user');
		$send_data['wsPassword'] = Mage::getStoreConfig('trustedrating/data/trustedrating_password');
		$send_data['partnerPackage'] = 'partnerPackage';

		Mage::log($send_data);

		try {
			$client = new SoapClient($soap_url);
			$returnValue = 'SOAP_ERROR';
			
			$returnValue = $client->updateRatingWidgetState($send_data['tsId'], $send_data['activation'], $send_data['wsUser'], $send_data['wsPassword'], $send_data['partnerPackage'] );
				
		} catch (SoapFault $fault) {
			$errorText = "SOAP Fault: (faultcode: {$fault->faultcode}, faultstring:
			{$fault->faultstring})";
			Mage::log($errorText);
		}
		Mage::log($send_data['wsUser'].', '.$send_data['wsPassword']);
		Mage::getSingleton('core/session')->addNotice('returnValue: '.$returnValue);
		
		/*if($returnValue == 'SOAP_ERROR') {
			Mage::getSingleton('core/session')->addError('SOAP error: '.$errorText);
		}
		else {
			Mage::getSingleton('core/session')->addSuccess('Web Service Call was successful with result '.$returnValue);
		}
		*/
		
		
		
		
	}
}