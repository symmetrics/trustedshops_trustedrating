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
class Symmetrics_TrustedRating_Block_Widget_Abstract extends Mage_Core_Block_Template
{
	/**
	 * returns the widget link data if trusted rating status is active
	 * 
	 * @param boolean $type
	 * @return array
	 */
	public function getDataForWidget($type) 
	{
		$model = Mage::getModel('trustedrating/trustedrating');
		
		if ($model->getIsActive()) {
		    return null;
		}

        switch ($type) {
            case 'RATING':
			    return $model->getRatingWidgetData();	
            break;
            case 'EMAIL':
			    return $model->getEmailWidgetData();
            break;
            default:
                return null;
            break;
        }
	}
}