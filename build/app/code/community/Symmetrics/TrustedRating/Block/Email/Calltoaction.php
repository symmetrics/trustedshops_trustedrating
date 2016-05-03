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
 * @copyright 2015 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://github.com/symmetrics/trustedshops_trustedrating/
 * @link      http://www.symmetrics.de/
 * @link      http://www.de.cgi.com/
 */

/**
 * Support the call to action email widget.
 *
 * @category  Symmetrics
 * @package   Symmetrics_TrustedRating
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @copyright 2015 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://github.com/symmetrics/trustedshops_trustedrating/
 * @link      http://www.symmetrics.de/
 * @link      http://www.de.cgi.com/
 */
class Symmetrics_TrustedRating_Block_Email_Calltoaction extends Mage_Core_Block_Template
{
    /**
     * Current order to work with.
     *
     * @var Mage_Sales_Model_Order
     */
    protected $_order;

    /**
     * Caches given order or creates a mockup-object as fallback.
     *
     * @return string
     */
    protected function _toHtml()
    {
        $this->_order = $this->getOrder();
        if (Mage::helper('trustedrating')->isActive()) {
            // This case should only happen when previewing it in the admin.
            if (!$this->_order) {
                $this->_order = new Varien_Object();
            }
            return parent::_toHtml();
        } else {
            return '';
        }
    }

    /**
     * Returns requested store object.
     * 
     * @param int $storeId Specific store ID or null for current one.
     *
     * @return Mage_Core_Model_Store
     */
    public function getStore($storeId = null)
    {
        if (!$storeId && Mage::app()->getStore()->isAdmin()) {
            $storeId = $this->_order->getStoreId();
        }
        return Mage::app()->getStore($storeId);
    }

    /**
     * Generates a privacy link.
     *
     * @return string
     */
    public function getPrivacyLink()
    {
        return sprintf(
            '<a href="%s" title="%s">%s</a>',
            $this->__('PRIVACY_LINK_URL'),
            $this->__('PRIVACY_LINK_HINT'),
            $this->__('PRIVACY_LINK_LABEL')
        );
    }

    /**
     * Generates the rate now URL based on the currrent order.
     *
     * @return string
     */
    public function getRateNowUrl()
    {
        $tsId = Mage::getStoreConfig('trustedrating/data/trustedrating_id', $this->getStore());
        return $this->__('RATE_NOW_URL', $tsId) . '?' . http_build_query(
            array(
                'buyerEmail' => base64_encode($this->_order->getData('customer_email')),
                'shopOrderID' => base64_encode($this->_order->getIncrementId())
            )
        );
    }

    /**
     * Generates the rate later URL based on the currrent order.
     *
     * @return string
     */
    public function getRateLaterUrl()
    {
        $tsId = Mage::getStoreConfig('trustedrating/data/trustedrating_id', $this->getStore());
        return $this->__('RATE_LATER_URL') . '?' . http_build_query(
            array(
                'buyerEmail' => base64_encode($this->_order->getData('customer_email')),
                'shopOrderID' => base64_encode($this->_order->getIncrementId()),
                'shop_id' => $tsId,
                'days' => $this->getRateLaterInterval()
            )
        );
    }

    /**
     * Generates the rate now button image URL based on the currrent order.
     *
     * @return string
     */
    public function getRateNowButtonImageUrl()
    {
        $imageUrl = $this->_getData('rate_now_button_image_url');
        return substr($imageUrl, 0, 4) == 'http' ? $imageUrl : Mage::getUrl(null, array('_direct' => $imageUrl));
    }

    /**
     * Generates the rate later button image URL based on the currrent order.
     *
     * @return string
     */
    public function getRateLaterButtonImageUrl()
    {
        $imageUrl = $this->_getData('rate_later_button_image_url');
        return substr($imageUrl, 0, 4) == 'http' ? $imageUrl : Mage::getUrl(null, array('_direct' => $imageUrl));
    }
}
