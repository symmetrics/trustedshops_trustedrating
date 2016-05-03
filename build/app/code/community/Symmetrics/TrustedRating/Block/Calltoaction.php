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
 * Support the call to action widget.
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
class Symmetrics_TrustedRating_Block_Calltoaction extends Mage_Core_Block_Template
{
    /**
     * Current order to work with.
     *
     * @var Mage_Sales_Model_Order
     */
    protected $_order;

    /**
     * Caches given order.
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $orderId = Mage::getSingleton('checkout/type_onepage')->getCheckout()->getLastOrderId();
        $this->_order = Mage::getModel('sales/order')->load($orderId);
    }

    /**
     * Returns the increment ID of the current order.
     *
     * @return string
     */
    public function getOrderIncrementId()
    {
        return $this->_order->getIncrementId();
    }

    /**
     * Returns the email address of the current order.
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->_order->getData('customer_email');
    }

    /**
     * Returns the total sum of the current order.
     *
     * @return float
     */
    public function getTotalSum()
    {
        return $this->_order->getGrandTotal();
    }

    /**
     * Returns the currency code of the current order.
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->_order->getOrderCurrencyCode();
    }

    /**
     * Returns the payment method code of the current order.
     *
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->_order->getPayment()->getMethodInstance()->getCode();
    }
}
