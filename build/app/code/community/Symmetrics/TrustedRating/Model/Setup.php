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
 * Setup model
 *
 * @category  Symmetrics
 * @package   Symmetrics_TrustedRating
 * @author    symmetrics gmbh <info@symmetrics.de>
 * @author    Siegfried Schmitz <ss@symmetrics.de>
 * @copyright 2010 symmetrics gmbh
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_TrustedRating_Model_Setup extends Mage_Eav_Model_Entity_Setup
{
    /*
     * Config paths for trustedratingmaiil
     */
    const XML_PATH_TRUSTEDRATINGMAIL = 'default/trustedratingmail/emails/default';

    /**
     * Get config data
     * 
     * @return array
     */
    public function getConfigData()
    {
        return Mage::getConfig()->getNode('default/trustedratingmail')->asArray();
    }
    
    /**
     * Get config node
     * 
     * @param string $node      main node
     * @param string $childNode child Node
     *
     * @return string
     */
    private function getConfigNode($node, $childNode = null)
    {
        $configData = $this->getConfigData();
        if ($childNode) {
            return $configData[$node][$childNode];
        } else {
            return $configData[$node];
        }
    }
    
    /**
     * Get email from config
     *
     * @return string
     */
    public function getConfigEmails()
    {
        return $this->getConfigNode('emails', 'default');
    }

    /**
     * Get email from config
     *
     * @param string $key Config path last key.
     *
     * @return string
     */
    public function getTrustedratingEmails($key = null)
    {
        return Mage::getConfig()
            ->getNode(self::XML_PATH_TRUSTEDRATINGMAIL . ($key ? '/' . $key : ''))
            ->asArray();
    }
    
    /**
     * Get content of template file
     * 
     * @param string $filename Name of File
     *
     * @return file
     */
    public function getTemplateContent($filename)
    {
        return file_get_contents(Mage::getBaseDir() . '/' . $filename);
    }
    
    /**
     * Create transaction email template
     * 
     * @param array $emailData collected data for email template
     *
     * @return int temlate id 
     */
    public function createEmail($emailData)
    {
        try {
            Mage::getModel('core/email_template')->loadByCode($emailData['template_code'])->delete();
        } catch (Exception $exception) {
            Mage::logException($exception);
        }
        $model = Mage::getModel('core/email_template');
        $template = $model->setTemplateSubject($emailData['template_subject'])
            ->setTemplateCode($emailData['template_code'])
            ->setTemplateText($this->getTemplateContent($emailData['text']))
            ->setTemplateType($emailData['template_type'])
            ->setModifiedAt(Mage::getSingleton('core/date')->gmtDate())
            ->save();

        $this->setConfigData($emailData['config_data_path'], $template->getId());
        
        return $template->getId();
    }
}
