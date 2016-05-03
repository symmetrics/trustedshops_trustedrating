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
 * @copyright 2009-2015 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://github.com/symmetrics/trustedshops_trustedrating/
 * @link      http://www.symmetrics.de/
 * @link      http://www.de.cgi.com/
 */

/**
 * System config model to configure Trustbadge snippet tab.
 *
 * @category  Symmetrics
 * @package   Symmetrics_TrustedRating
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @copyright 2009-2015 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://github.com/symmetrics/trustedshops_trustedrating/
 * @link      http://www.symmetrics.de/
 * @link      http://www.de.cgi.com/
 */
class Symmetrics_TrustedRating_Block_Adminhtml_System_Config_Trustbadge
    extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
    implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * Render to return the html-content of sub-menu,
     * under Admin Panel / System / Configuration.
     *
     * @param Varien_Data_Form_Element_Abstract $element element
     *
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = $this->_getHeaderHtml($element);
        foreach ($element->getSortedElements() as $field) {
            if (strpos($field->getName(), 'trustbadge_code_snippet') !== false) {
                $field->setComment($this->getTrustbadgeGenerationUrl());
            }
            $html.= $field->toHtml();
        }
        $html .= $this->_getFooterHtml($element);
        return $html;
    }

    /**
     * Get Trustbadge widget generation url.
     *
     * @return string
     */
    public function getTrustbadgeGenerationUrl()
    {
        $storeId = Mage::getSingleton('adminhtml/config_data')->getScopeId();
        $trustedratingId = Mage::getStoreConfig('trustedrating/data/trustedrating_id', $storeId);
        $url = Mage::getStoreConfig('trustedrating/trustbadge_generation_url', $storeId);
        $language = explode('_', Mage::app()->getLocale()->getLocaleCode());
        $language = $language[0]; // PHP <5.4 does not understand the function()[] syntax.
        $vars = array(
            'SHOP_ID' => $trustedratingId,
            'LANG' => "{$language}",
            'SHOP_SOFTWARE' => 'MAGENTO_MRG',
            'SHOP_VERSION' => Mage::getVersion(),
            'PLUGIN_VERSION' => (string) Mage::getConfig()->getNode()->modules->Symmetrics_TrustedRating->version
        );
        $url = str_replace(array_keys($vars), array_values($vars), $url);
        $link = '<a href=' . $url . ' target="_blank">' . $this->__('TB_CODE_SNIPPET_LINK_LABEL') . '</a>';
        return $this->__('TB_CODE_SNIPPET_LINK_TEXT', $link);
    }
}
