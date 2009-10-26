<?php
/**
 * Symmetrics_TrustedRating_Admin_RegistrationController
 *
 * @category Symmetrics
 * @package Symmetrics_TrustedRating
 * @author symmetrics gmbh <info@symmetrics.de>, Siegfried Schmitz <ss@symmetrics.de>
 * @copyright symmetrics gmbh
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Symmetrics_TrustedRating_Admin_RegistrationController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
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
		$link = "https://www.trustedshops.com/bewertung/anmeldung.html?partnerPackage=partnerPackage";
		
		foreach($params as $key => $param) {
			if($param) {
				$link .= '&' . $key . '=' . urlencode($param);
			}
		}
		$this->getResponse()->setRedirect($link);
    }
}