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
 * @author    symmetrics gmbh <info@symmetrics.de>
 * @author    Siegfried Schmitz <ss@symmetrics.de>
 * @copyright 2009 Symmetrics Gmbh
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
 
 /**
  * Symmetrics_TrustedRating_Block_Registrationlink
  *
  * @category  Symmetrics
  * @package   Symmetrics_TrustedRating
  * @author    symmetrics gmbh <info@symmetrics.de>
  * @author    Siegfried Schmitz <ss@symmetrics.de>
  * @copyright 2009 Symmetrics Gmbh
  * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
  * @link      http://www.symmetrics.de/
  */
class Symmetrics_TrustedRating_Block_Registrationlink extends Mage_Core_Block_Template
{
    /**
     * return the Trusted Rating Registration Link and the js-function to put it into the comment-field
     * 
     * @return string
     */
    protected function _toHtml()
    {   
        $request = Mage::app()->getFrontController()->getRequest();
        $currentUrl = $request->getServer('PATH_INFO');

        if (!strpos($currentUrl, 'section/trustedrating')) {
            return null;
        }

        $registrationlinkData = $this->_getRegistrationLinkData();
        $target = $registrationlinkData['target'];
        $text = $registrationlinkData['text'];
        $languageLabel = $this->__('Shop Language'). '<font color="red">*</font>';

        $link = '<a href = "' . $target . '" target = "_blank">' . $text . '</a>';

        $registrationLink = '<script type="text/javascript">
            document.observe(\'dom:loaded\', function() {
                var comment = $$(\'#trustedrating_trustedrating_registration div\')[0];
                comment.innerHTML = \'' . $link . '\';
                var languageLabel = $$(\'#trustedrating_data label\')[1];
                languageLabel.innerHTML = \'' . $languageLabel . '\';
            });
        </script>';
        
        return $registrationLink;
    }
    
    /**
     * Returns the data for the registration link
     * 
     * @return array
     */
    private function _getRegistrationLinkData()
    {
        return array(
            'target' => Mage::getBaseUrl() . 'admin/registration',
            'text' => $this->__('Link to the registration on Trusted Shops Rating'),
        );
    }
}