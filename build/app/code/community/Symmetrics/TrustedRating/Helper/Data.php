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
 * @author    Yauhen Yakimovich <yy@symmetrics.de>
 * @copyright 2010 symmetrics gmbh
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
 
/**
 * Default helper class, return config values
 *
 * @category  Symmetrics
 * @package   Symmetrics_TrustedRating
 * @author    symmetrics gmbh <info@symmetrics.de>
 * @author    Siegfried Schmitz <ss@symmetrics.de>
 * @author    Yauhen Yakimovich <yy@symmetrics.de>
 * @copyright 2010 symmetrics gmbh
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_TrustedRating_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @const CONFIG_STATUS_PATH system config path to status settings
     */
    const CONFIG_STATUS_PATH = 'trustedrating/status';
    
    /**
     * Get store config by node and key
     *
     * @param string $node node
     * @param string $key  key
     *
     * @return string
     */
    public function getConfig($node, $key)
    {
        return Mage::getStoreConfig($node . '/' . $key, Mage::app()->getStore());
    }
    
    /**
     * Get module specific config from system configuration
     * 
     * @param string $key config key
     * 
     * @return mixed
     */
    public function getModuleConfig($key)
    {
        return $this->getConfig(self::CONFIG_STATUS_PATH, $key);
    }

    /**
     * Get the activity status from store config
     *
     * @return string
     */
    public function getIsActive()
    {
        return $this->getModuleConfig('trustedrating_active');
    }

    /**
     * Get the trusted rating id from store config
     *
     * @return string
     */
    public function getTsId()
    {
        return Mage::getStoreConfig('trustedrating/data/trustedrating_id');
    }
    
    /**
     * Check if TS id is correct and module is active.
     *
     * @return boolean
     */
    public function canShowWidget() 
    {
        if ($this->getTsId() && $this->getIsActive()) {
            return true;
        }
        
        return false;
    }

    /**
     * Get the "incluce orders since" setting from store config
     *
     * @return Zend_Date
     */
    public function getActiveSince()
    {
        return $this->getConfig('trustedrating/data', 'active_since');
    }
    
    /**
     * Get order ID by shipment ID
     *
     * @param int $shipmentId Shipment Id
     *
     * @return int
     */
    public function getOrderId($shipmentId)
    {
        $shipment = Mage::getModel('sales/order_shipment')->load($shipmentId);
        
        return $shipment->getData('order_id');
    }
    
    /**
     * Get customer email by shipment Id
     *
     * @param int $shipmentId Shipment Id
     *
     * @return string
     */
    public function getCustomerEmail($shipmentId)
    {
        $shipment = Mage::getModel('sales/order_shipment')->load($shipmentId);
        $customerId = $shipment->getData('customer_id');
        $customer = Mage::getModel('customer/customer')->load($customerId);

        return $customer->getData('email');
    }
}
