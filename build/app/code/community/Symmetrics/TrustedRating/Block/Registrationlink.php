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
 * Generate registration link
 *
 * @category  Symmetrics
 * @package   Symmetrics_TrustedRating
 * @author    symmetrics gmbh <info@symmetrics.de>
 * @author    Siegfried Schmitz <ss@symmetrics.de>
 * @copyright 2010 symmetrics gmbh
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_TrustedRating_Block_Registrationlink extends Mage_Core_Block_Template
{
    /**
     * @const REGISTRATIONPATH request path to registration controller
     */
    const REGISTRATIONPATH = 'trustedrating/registration';
    
    /**
     * Check if the Trusted Rating section is selected 
     * 
     * @return bool
     */
    public function isActive()
    {
        $request = Mage::app()->getFrontController()->getRequest();
        $currentUrl = $request->getServer('PATH_INFO');

        if (!strpos($currentUrl, 'section/trustedrating')) {
            
            return false;
        }
        
        return true;
    }
    
    /**
     * Generate registration link target
     * 
     * @return string
     */
    public function getRegistrationLinkTarget()
    {
        $urlModel = Mage::getModel('adminhtml/url');
        if ($urlModel->useSecretKey()) {
            $url = $urlModel->addSessionParam()
                ->getUrl(self::REGISTRATIONPATH);
        } else {
            $url = Mage::getUrl(self::REGISTRATIONPATH);
        }
        
        return $url;
    }
}
