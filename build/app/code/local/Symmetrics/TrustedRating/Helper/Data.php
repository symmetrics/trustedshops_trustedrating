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
	/**
     * get store config by node and key
	 *
	 * @param string $node
	 * @param string $key
	 * @return string
     */
	public function getConfig($node, $key)
	{
		return Mage::getStoreConfig($node . '/' . $key, Mage::app()->getStore());
	}
}