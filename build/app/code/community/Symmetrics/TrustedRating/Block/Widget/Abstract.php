<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category  Symmetrics
 * @package   Symmetrics_TrustedRating
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Siegfried Schmitz <ss@symmetrics.de>
 * @copyright 2009-2012 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */

/**
 * Abstract widget class for coomon use
 *
 * @category  Symmetrics
 * @package   Symmetrics_TrustedRating
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Siegfried Schmitz <ss@symmetrics.de>
 * @copyright 2009-2012 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_TrustedRating_Block_Widget_Abstract extends Mage_Core_Block_Template
{
    /**
     * Generate the widget link data if trusted rating status is active and data are present for the current language
     *
     * @param boolean $type RATING|EMAIL widget type
     *
     * @return array|null
     */
    public function getDataForWidget($type)
    {
        $model = Mage::getModel('trustedrating/trustedrating');

        if (!$model->getIsActive() || !$model->checkLocaleData()) {
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
