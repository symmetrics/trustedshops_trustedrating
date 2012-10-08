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
 * Drop-Down for month
 *
 * @category  Symmetrics
 * @package   Symmetrics_TrustedRating
 * @author    symmetrics - a CGI Group brand <info@symmetrics.de>
 * @author    Eric Reiche <er@symmetrics.de>
 * @copyright 2009-2012 symmetrics - a CGI Group brand
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_TrustedRating_Model_System_Date_Month extends Symmetrics_TrustedRating_Model_System_Date_Abstract
{
    /**
     * Generate array with 1 to 12
     * 
     * @return array
     */
    public function getAllOptions()
    {
        return $this->buildNumericOptions(1, 12);
    }
}
