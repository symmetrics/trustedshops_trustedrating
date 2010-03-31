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
 * Set the "include shippings after" date
 *
 * @category  Symmetrics
 * @package   Symmetrics_TrustedRating
 * @author    symmetrics gmbh <info@symmetrics.de>
 * @author    Eric Reiche <er@symmetrics.de>
 * @copyright 2010 symmetrics gmbh
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */

$installer = $this;
$installer->startSetup();

$todayDate = Mage::app()->getLocale()->date();

$config = array(
    'datelimit_y' => 'yyyy',
    'datelimit_m' => 'MM',
    'datelimit_d' => 'dd',
    'datelimit_h' => 'HH',
    'datelimit_i' => 'mm',
);
foreach ($config as $key => $value) {
    $installer->setConfigData('trustedrating/status/' . $key, $todayDate->toString($value));
}

$installer->endSetup();
