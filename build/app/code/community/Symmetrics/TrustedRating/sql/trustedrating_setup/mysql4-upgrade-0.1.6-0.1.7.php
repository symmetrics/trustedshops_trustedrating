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
 * @author    Eric Reiche <er@symmetrics.de>
 * @copyright 2009 Symmetrics Gmbh
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */

$installer = $this;
$installer->startSetup();

$todayDate = Mage::app()->getLocale()->date();

$config = array(
    'datelimit_y' => $todayDate->toString('yyyy'),
    'datelimit_m' => $todayDate->toString('MM'),
    'datelimit_d' => $todayDate->toString('dd'),
    'datelimit_h' => $todayDate->toString('HH'),
    'datelimit_i' => $todayDate->toString('mm'),
);
foreach ($config as $key => $value) {
    $installer->setConfigData('trustedrating/status/' . $key, $value);
}

$installer->endSetup();