<?php
/**
 * @category Symmetrics
 * @package Symmetrics_TrustedRating
 * @author symmetrics gmbh <info@symmetrics.de>, Siegfried Schmitz <ss@symmetrics.de>
 * @copyright symmetrics gmbh
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Symmetrics_TrustedRating_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getConfig($who, $key)
	{
		$path = $who.'/' . $key;
		return Mage::getStoreConfig($path, Mage::app()->getStore());
	}
}