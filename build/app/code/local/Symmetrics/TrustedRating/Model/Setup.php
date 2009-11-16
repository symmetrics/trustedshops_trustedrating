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
 * @copyright 2009 Symmetrics Gmbh
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
 
 /**
  * Symmetrics_TrustedRating_Model_Setup
  *
  * @category  Symmetrics
  * @package   Symmetrics_TrustedRating
  * @author    symmetrics gmbh <info@symmetrics.de>
  * @author    Siegfried Schmitz <ss@symmetrics.de>
  * @copyright 2009 Symmetrics Gmbh
  * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
  * @link      http://www.symmetrics.de/
  */
class Symmetrics_TrustedRating_Model_Setup extends Mage_Eav_Model_Entity_Setup
{
    /**
     * get config data
     * 
     * @return array
     */
    public function getConfigData()
    {
        return Mage::getConfig()->getNode('default/trustedratingmail')->asArray();
    }
    
    /**
     * get config node‚
     * 
     * @param string $node     node
     *
     * @param string $nextNode next Node
     *
     * @return string
     */
    private function getConfigNode($node, $nextNode = null)
    {
        $configData = $this->getConfigData();
        if ($nextNode) {
            return $configData[$node][$nextNode];
        } else {
            return $configData[$node];
        }
    }
    
    /**
     * get email from config
     *
     * @return string
     */
    public function getConfigEmails()
    {
        return $this->getConfigNode('emails', 'default');
    }
    
    /**
     * get content from template file
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
     * create Transaction Email
     * 
     * @param array $emailData collected Data for Email
     *
     * @return void
     */
    public function createEmail($emailData)
    {
        try {
            Mage::getModel('core/email_template')->loadByCode($emailData['template_code'])->delete();
        } catch (Exception $e) {
                Mage::log($e->getMessage());
        }
        $model = Mage::getModel('core/email_template');
            $template = $model->setTemplateSubject($emailData['template_subject'])
                ->setTemplateCode($emailData['template_code'])
                ->setTemplateText($this->getTemplateContent($emailData['text']))
                ->setTemplateType($emailData['template_type'])
                ->setModifiedAt(Mage::getSingleton('core/date')->gmtDate())
                ->save();

            $this->setConfigData($emailData['config_data_path'], $template->getId());
    }
}