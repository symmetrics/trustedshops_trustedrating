<?php
/**
 * Symmetrics_TrustedRating_Model_Observer
 *
 * @category Symmetrics
 * @package Symmetrics_TrustedRating
 * @author symmetrics gmbh <info@symmetrics.de>, Siegfried Schmitz <ss@symmetrics.de>
 * @copyright symmetrics gmbh
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
$installer->startSetup();

// execute emails
foreach ($this->getConfigEmails() as $name => $data) {
    if ($data['execute'] == 1) {
        $this->createEmail($data);
    }
}

$query = <<< EOF
    CREATE TABLE IF NOT EXISTS {$this->getTable('symmetrics_trustedrating_emails')} (
      `entity_id` int(11) NOT NULL AUTO_INCREMENT,
      `shippment_id` int(11) NOT NULL,
      PRIMARY KEY (`entity_id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
EOF;

$installer->run($query);
$installer->setConfigData('trustedrating/trustedrating_email/days', '3');

$installer->endSetup();