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
class Symmetrics_TrustedRating_Model_Setup extends Mage_Eav_Model_Entity_Setup
{
    /**
     * 
     * 
     * @param array example
     * @return string
     */
    public function getConfigData()
    {
        return Mage::getConfig()->getNode('default/trustedratingmail')->asArray();
    }
    
    /**
     * 
     * 
     * @param array example
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
     * 
     * 
     * @param array example
     * @return string
     */
    public function getConfigEmails()
    {
        return $this->getConfigNode('emails', 'default');
    }
    
    /**
     * 
     * 
     * @param array example
     * @return string
     */
    public function getTemplateContent($filename)
    {
        return file_get_contents(Mage::getBaseDir() . '/' . $filename);
    }
    
    /**
     * 
     * 
     * @param array example
     * @return string
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