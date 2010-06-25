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
     * Get the "incluce orders since" setting from store config
     *
     * @return Zend_Date
     */
    public function getActiveSince()
    {
        $activeSince = $this->getConfig('trustedrating/data', 'active_since');
        $activeDate = new Zend_Date($activeSince);
        
        return $activeDate;
    }
}
