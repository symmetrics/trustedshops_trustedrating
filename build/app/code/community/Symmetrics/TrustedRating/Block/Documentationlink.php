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
 * @copyright 2010 symmetrics gmbh
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
 
/**
 * Generate documentation links for backend
 *
 * @category  Symmetrics
 * @package   Symmetrics_TrustedRating
 * @author    symmetrics gmbh <info@symmetrics.de>
 * @author    Siegfried Schmitz <ss@symmetrics.de>
 * @copyright 2010 symmetrics gmbh
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_TrustedRating_Block_Documentationlink extends Mage_Core_Block_Template
{
    /**
     * Generate the Documentation - Pdf -Link and the JS-function to put it into the comment-field
     * 
     * @return string
     */
    protected function _toHtml()
    {
        $documentationlinkData = $this->_getDocumentationLinkData();
        $target = $documentationlinkData['target'];
        $text = $documentationlinkData['text'];
        $link = '<a href = "' . $target . '" target = "_blank">' . $text . '</a>';

        $pdflink = '<script type="text/javascript">
            document.observe(\'dom:loaded\', function() {
                $(\'trustedrating_info\').style.display=\'block\';
                $(\'pdflink\').innerHTML = \'' . $link . '\';
            });
        </script>';
        
        return $pdflink;
    }
    
    /**
     * Generate the data for the pdf link
     * 
     * @return array
     */
    private function _getDocumentationLinkData()
    {
        return array(
            'target' => Mage::getBaseUrl('web') . 'media/TS_Kundenbewertung_Magento_v1_0.pdf',
            'text' => $this->__('Trusted Shops Documentation'),
        );
    }
}
