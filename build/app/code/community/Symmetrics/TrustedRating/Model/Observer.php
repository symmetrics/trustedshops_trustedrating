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
 * @author    Yauhen Yakimovich <yy@symmetrics.de>
 * @copyright 2010 symmetrics gmbh
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */

/**
 * Observer model
 *
 * @category  Symmetrics
 * @package   Symmetrics_TrustedRating
 * @author    symmetrics gmbh <info@symmetrics.de>
 * @author    Siegfried Schmitz <ss@symmetrics.de>
 * @author    Yauhen Yakimovich <yy@symmetrics.de>
 * @copyright 2010 symmetrics gmbh
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.symmetrics.de/
 */
class Symmetrics_TrustedRating_Model_Observer
{
    /**
     * Config path to email template id
     *
     * @var string
     */
    const XML_PATH_CONFIG_EMAIL_TEMPLATE = 'trustedrating/trustedrating_email/template';

    /**
     * Email identity path
     *
     * @var string
     */
    const XML_PATH_EMAIL_IDENTITY = 'sales_email/order/identity';

    /**
     * Change the status (active, inactive) by sending an api call to Trusted Shops
     *
     * @param Varien_Event_Observer $observer Observer
     *
     * @return void
     */
    public function changeTrustedRatingStatus($observer)
    {
        $storeId = $observer->getStore();
        $soapUrl = Mage::helper('trustedrating')->getConfig('soapapi', 'url');
        $sendData = $this->_getSendData($storeId);
        $returnValue = $this->_callTrustedShopsApi($sendData, $soapUrl);
        $returnString = Mage::helper('trustedrating')->__('TrustedShops return value: ');
        Mage::getSingleton('core/session')->addNotice($returnString . $returnValue);
    }

    /**
     * Checks the shippings which will get an email
     *
     * @return void
     */
    public function checkSendRatingEmail()
    {
        $model = Mage::getModel('trustedrating/trustedrating');
        if ($this->isActive() && $shipmentIds = $model->checkShippings()) {
            $this->_sendTrustedRatingMails($shipmentIds);
        }
    }

    /**
     * Send mail and save entry to db
     *
     * @param array $shipmentIds Shipment IDs
     *
     * @return void
     */
    private function _sendTrustedRatingMails($shipmentIds)
    {
       foreach ($shipmentIds as $shipmentId) {
           $orderId = $this->getHelper()->getOrderId($shipmentId);
           $order = Mage::getModel('sales/order')->load($orderId);
           $incrementId = $order->getRealOrderId();
           $customerEmail = $this->getHelper()->getCustomerEmail($shipmentId);

           $this->_sendTransactionalMail($incrementId, $customerEmail);
           $this->_saveShipmentIdToTable($shipmentId);
       }
    }

    /**
     * Send transactional email
     *
     * @param string $incrementId   Order incriment Id
     * @param string $customerEmail Customer Email
     *
     * @return void
     */
    private function _sendTransactionalMail($incrementId, $customerEmail)
    {
        $orderStoreId = Mage::getModel('sales/order')->loadByIncrementId($incrementId)->getStoreId();
        $emailWidgetLink = $this->_getEmailWidgetLink($incrementId, $customerEmail, $orderStoreId);

        Mage::getModel('core/email_template')->sendTransactional(
            Mage::getStoreConfig(self::XML_PATH_CONFIG_EMAIL_TEMPLATE, $orderStoreId),
            Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY),
            $customerEmail, //replace with your own when you want to test it
            $customerEmail,
            array('emailwidget' => $emailWidgetLink),
            $orderStoreId
        );
    }

    /**
     * Generate email widget code
     *
     * @param string $incrementId   Order incriment Id
     * @param string $customerEmail Customer Email
     * @param int    $orderStoreId  Order store id, used for multistore logic
     *
     * @return string
     */
    private function _getEmailWidgetLink($incrementId, $customerEmail, $orderStoreId)
    {           
        Mage::log('_getEmailWidgetLink');      
        $model = Mage::getModel('trustedrating/trustedrating');

        $buyerEmail = base64_encode($customerEmail);
        $incrementId = base64_encode($incrementId);       
        $link = '<a href="' . $model->getEmailRatingLink() . '_' . $model->getTsId() . '.html';
        $params = '&buyerEmail=' . $buyerEmail . '&shopOrderID=' . $incrementId . '">';    
        
        $baseUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN);     
        $widgetPath = Symmetrics_TrustedRating_Model_Trustedrating::RATING_BUTON_LOCAL_PATH;   
        
        $ratingLinkData = $model->getRatingLinkData('emailratingimage', $orderStoreId);
        $widget = '<img src="' . $baseUrl . $widgetPath . $ratingLinkData . '"/></a>';            
                 
        Mage::log($baseUrl);                                                                                     
        Mage::log($widgetPath);                                                                                 
        Mage::log($ratingLinkData);                                                                  

        return $link . $params . $widget;
    }

    /**
     * Save shipping ID of customers which got an email to table
     *
     * @param int $shipmentId Shipping Id
     *
     * @return void
     */
    private function _saveShipmentIdToTable($shipmentId)
    {
        $mailModel = Mage::getModel('trustedrating/mail');
        $mailModel->setShippmentId($shipmentId)
            ->save();
    }

    /**
     * Collect the data for sending to Trusted Shops
     *
     * @param int $storeId storeID
     *
     * @return array
     */
    private function _getSendData($storeId)
    {
        $sendData = array();

        $sendData['tsId'] = Mage::getStoreConfig('trustedrating/data/trustedrating_id', $storeId);
        $sendData['activation'] = $this->isActive();
        $sendData['wsUser'] = $this->getHelper()->getConfig('soapapi', 'wsuser');
        $sendData['wsPassword'] = $this->getHelper()->getConfig('soapapi', 'wspassword');
        $sendData['partnerPackage'] = $this->getHelper()->getConfig('soapapi', 'partnerpackage');

        return $sendData;
    }

    /**
     * Call the SOAP API of Trusted Shops
     *
     * @param array $sendData data to send
     * @param array $soapUrl  soap url
     *
     * @return string
     */
    private function _callTrustedShopsApi($sendData, $soapUrl)
    {
        $returnValue = 'SOAP_ERROR';
        try {
            $client = new SoapClient($soapUrl);
            $returnValue = $client->updateRatingWidgetState(
                $sendData['tsId'],
                $sendData['activation'],
                $sendData['wsUser'],
                $sendData['wsPassword'],
                $sendData['partnerPackage']
            );
        } catch (SoapFault $fault) {
            $errorText = 'SOAP Fault: (faultcode: ' . $fault->faultcode;
            $errorText.= ', faultstring: ' . $fault->faultstring . ')';
            Mage::log($errorText);
        }

        return $returnValue;
    }

    /**
     * Return helper object
     *
     * @return Symmetrics_TrustedRating_Helper_Data
     */
    public function getHelper()
    {
        return Mage::helper('trustedrating');
    }

    /**
     * Check wether module is active or not
     *
     * @return boolean
     */
    public function isActive()
    {
        return (bool)$this->getHelper()->getIsActive();
    }
}
