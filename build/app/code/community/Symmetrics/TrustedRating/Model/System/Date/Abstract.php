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
 * @author    Eric Reiche <er@symmetrics.de>
 * @copyright 2009-2012 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
 
/**
 * Abstract class which provides some methods for drop-downs in the backend
 *
 * @category  Symmetrics
 * @package   Symmetrics_TrustedRating
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Eric Reiche <er@symmetrics.de>
 * @copyright 2009-2012 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
abstract class Symmetrics_TrustedRating_Model_System_Date_Abstract
    extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * Return option array
     * 
     * @return array
     */
    public function getOptionArray()
    {
        return $this->getAllOptions();
    }
    
    /**
     * Return array of progressive numbers
     *
     * @param int $start start the for-loop at
     * @param int $end   end the for-loop at
     * 
     * @return array
     */
    public function buildNumericOptions($start, $end)
    {
        $options = array();
        for ($option = $start; $option <= $end; $option++) {
            $options[$option] = $option;
        }
        return $options;
    }
    
    /**
     * Return option array
     * 
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }
}
