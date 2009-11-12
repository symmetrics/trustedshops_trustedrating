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
     * @param string $node
     * @param string $nextNode
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
     * @param string $filename
     * @return file
     */
    public function getTemplateContent($filename)
    {
        return file_get_contents(Mage::getBaseDir() . '/' . $filename);
    }
    
    /**
     * create Transaction Email
     * 
     * @param array $emailData
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