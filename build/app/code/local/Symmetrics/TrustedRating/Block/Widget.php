<?php
/**
 * Symmetrics_TrustedRating_Block_Widget
 *
 * @category Symmetrics
 * @package Symmetrics_TrustedRating
 * @author symmetrics gmbh <info@symmetrics.de>, Siegfried Schmitz <ss@symmetrics.de>
 * @copyright symmetrics gmbh
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Symmetrics_TrustedRating_Block_Widget extends Mage_Core_Block_Template
{
	/**
     * returns the widget if the trusted rating status
	 * is active in the store config
	 *
	 * @return string
	 */
    protected function _toHtml()
    {
    	$model = Mage::getModel('trustedrating/trustedrating');
		if ($model->getIsActive()) {
			return $model->getImage();
		}
    	else {
    		return null;
    	}
    }
}