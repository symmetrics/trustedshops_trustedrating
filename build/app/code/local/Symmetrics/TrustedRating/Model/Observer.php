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
class Symmetrics_TrustedRating_Model_Observer
{
    const XML_PATH_SYMMETRICS_TRUSTEDRATING_EMAIL_TEMPLATE = 'sales_email/trustedrating/template';
    const XML_PATH_EMAIL_IDENTITY = 'sales_email/order/identity';
    /**
     * Get table name
     *
     * @param string $tableName
     * @return string
     */
    public function getTable($tableName) {
        return Mage::getSingleton('core/resource')->getTableName($tableName);
    }
        
    /**
     * change the activity status (active, inactive) by sending an api call to trusted rating
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function changeTrustedRatingStatus($observer) 
    {   
        $storeId = $observer->getStore();
        $soapUrl = Mage::helper('trustedrating')->getConfig('soapapi', 'url');
        $sendData = $this->_getSendData($storeId);
        $returnValue = $this->_callTrustedShopsApi($sendData, $soapUrl);
        
        Mage::getSingleton('core/session')->addNotice('returnValue: ' . $returnValue);
    }
    
    /**
     * enter description here
     * 
     * @param Varien_Event_Observer $observer
     * @return void
     */
     public function checkSendRatingEmail($observer) 
     {
        if($shippmentIds = $this->_checkShippings()) {
            $this->_sendTrustedRatingMails($shippmentIds);
        }
        else {
            Mage::log('nothing to send');
        }
     }
     
     /**
      * enter description here
      * 
      * @return array 
      */
      private function _checkShippings() 
      {
        if (!$dayInterval = $this->_getDayInterval()) {
            return false;
        }
        
        $shipments = Mage::getResourceModel('sales/order_shipment_collection')
            ->addAttributeToFilter('entity_id', array('nin' => $this->_getSendedIds()))
            ->addAttributeToFilter('created_at', array('from' => $dayInterval['from'], 'to' => $dayInterval['to']))
            ->load();
         
        if (!$shipments) {
            return false;
        }
        return $shipments->getAllIds();
      }
      
      /**
       * enter description here
       * 
       * @param array $orderIds
       * @return void
       */
       private function _sendTrustedRatingMails($shippmentIds) 
       {
           foreach ($shippmentIds as $shipmentId) {
               Mage::log('shipmentid: '.$shipmentId);
               $shipment = Mage::getModel('sales/order_shipment')->load($shipmentId);
               $orderId = $shipment->getData('order_id');
               $customer_id = $shipment->getData('customer_id');
               Mage::log('customerId: '.$customer_id);
               $customer = Mage::getModel('customer/customer')->load($customer_id);
               $customerEmail = $customer->getData('email');
               
               $mailTemplate = Mage::getModel('core/email_template');
   			   $template = Mage::getStoreConfig(self::XML_PATH_SYMMETRICS_TRUSTEDRATING_EMAIL_TEMPLATE);
   			   
   			   $mailTemplate->sendTransactional(
                       $template,
                       Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY),
                       $customerEmail, //replace through your own when you want to test it
                       $customerEmail,
                       array (
                           'emailwidget' => $this->_getEmailWidgetLink($orderId, $customerEmail)
                       )		
                   );
            Mage::log('mail sendet to user '.$customerEmail);
            $this->_saveShippmentIdToTable($shipmentId);
            }
            
       }
       
       /**
        * enter description here
        * 
        * @param array example
        * @return string
        */
        private function _getEmailWidgetLink($orderId, $customerEmail) 
        {
            $model = Mage::getModel('trustedrating/trustedrating');
            $buyerEmail = base64_encode($customerEmail);
            $orderId = base64_encode($orderId);
            $baseUrl = Mage::getBaseUrl();
            if(strpos($baseUrl,'index.php')) {
                $baseUrl = substr(Mage::getBaseUrl(),0,strrpos(Mage::getBaseUrl(),'index.php'));
            }
            $link = '<a href="' . $model->getEmailRatingLink() . '_' . $model->getTsId() . '.html';
            $params = '&buyerEmail=' . $buyerEmail . '&shopOrderID=' . $orderId . '">';
            $widget = '<img src="' . $baseUrl . Symmetrics_TrustedRating_Model_Trustedrating::IMAGE_LOCAL_PATH . $this->_getRatingLinkData('emailratingimage') . '"/></a>';
            Mage::log($widget);
            return $link . $params . $widget;
        }
        
        private function _getRatingLinkData($type) 
         {
            $optionValue = Mage::getStoreConfig('trustedrating/data/trustedrating_ratinglanguage');
            $link = Mage::helper('trustedrating')->getConfig($type, $optionValue);
            return $link;
         }
       
       /**
        * enter description here
        * 
        * @param array example
        * @return string
        */
        private function _saveShippmentIdToTable($shipmentId) 
        {
            $write = Mage::getSingleton('core/resource')->getConnection('core_write');
            $sql = 'INSERT INTO '.$this->getTable('symmetrics_trustedrating_emails').' (shippment_id) VALUES ('.$shipmentId.');';
            $write->query($sql);
        }
       
       /**
        * enter description here
        * 
        * @param array example
        * @return string
        */
        private function _getSendedIds() 
        {
            $read = Mage::getSingleton('core/resource')->getConnection('core_read');
            $sql = 'SELECT shippment_id FROM '.$this->getTable('symmetrics_trustedrating_emails').';';
            $readresult = $read->query($sql);
            if (!$result = $readresult->fetchAll()) {
                return array('');
            }
            
            foreach ($result as $id) {
                $shippmentIds[] = $id['shippment_id'];
            }
            
            return $shippmentIds;
        }
        
        /**
         * enter description here
         * 
         * @param array example
         * @return string
         */
         private function _getDayInterval() 
         {
             $from = '2009-01-01 00:00:00';
             
             if(!$dayInterval = Mage::getStoreConfig('trustedrating/trustedrating_email/days')) {
                 return false;
             }
             $intervalSeconds = $dayInterval * 24 * 60 * 60;
             //rechne heute - dayInterval Tage
             $date = new Zend_Date();
             $timestamp = $date->get();
             
             $diff = $timestamp - $intervalSeconds;
             
             return array('from' => $from,
                          'to' => date("Y-m-d H:i:s", $diff)
             );
         }
    
    /**
     * collect the data for sending to trusted rating
     *
     * @return array
     */ 
    private function _getSendData($storeId) 
    {
        Mage::log($storeId);

        $sendData = array();
        
        $sendData['tsId'] = Mage::getStoreConfig('trustedrating/data/trustedrating_id', $storeId);
        $sendData['activation'] = Mage::getStoreConfig('trustedrating/status/trustedrating_active', $storeId);
        $sendData['wsUser'] = Mage::helper('trustedrating')->getConfig('soapapi', 'wsuser');
        $sendData['wsPassword'] = Mage::helper('trustedrating')->getConfig('soapapi', 'wspassword');
        $sendData['partnerPackage'] = Mage::helper('trustedrating')->getConfig('soapapi', 'partnerpackage');
        Mage::log($sendData);
        return $sendData;   
    }
    
    /**
     * calling the soap api from trusted rating
     * 
     * @param array $send_data
     * @param array $soap_url
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
            $errorText = 'SOAP Fault: (faultcode: ' . $fault->faultcode . ', faultstring: ' . $fault->faultstring . ')';
            Mage::log($errorText);
        }
        
        return $returnValue;
    }
}