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
  * Symmetrics_TrustedRating_Model_Observer
  *
  * @category  Symmetrics
  * @package   Symmetrics_TrustedRating
  * @author    symmetrics gmbh <info@symmetrics.de>
  * @author    Siegfried Schmitz <ss@symmetrics.de>
  * @copyright 2009 Symmetrics Gmbh
  * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
  * @link      http://www.symmetrics.de/
  */
class Symmetrics_TrustedRating_Model_Observer
{
    /**
     * config path to email template
     */
    const XML_PATH_SYMMETRICS_TRUSTEDRATING_EMAIL_TEMPLATE = 'sales_email/trustedrating/template';
    
    /**
     * email identity path
     */
    const XML_PATH_EMAIL_IDENTITY = 'sales_email/order/identity';
    
    /**
     * Get table name
     *
     * @param string $tableName Name of Table
     *
     * @return string
     */
    public function getTable($tableName) 
    {
        return Mage::getSingleton('core/resource')->getTableName($tableName);
    }
        
    /**
     * change the activity status (active, inactive) by sending an api call to trusted rating
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
        
        Mage::getSingleton('core/session')->addNotice('returnValue: ' . $returnValue);
    }
    
    /**
     * checks the shippings who needs an email
     * 
     * @param Varien_Event_Observer $observer Observer
     *
     * @return void
     */
     public function checkSendRatingEmail($observer) 
     {
        if ($shippmentIds = $this->_checkShippings()) {
            $this->_sendTrustedRatingMails($shippmentIds);
        } else {
            Mage::log('nothing to send');
        }
     }
     
     /**
      * gets all shippings who are older then x days and are not in table
      * 
      * @return boolean|array 
      */
      private function _checkShippings() 
      {
        if (!$dayInterval = $this->_getDayInterval()) {
            return false;
        }
        
        $from = $dayInterval['from'];
        $to = $dayInterval['to'];
        
        $shipments = Mage::getResourceModel('sales/order_shipment_collection')
            ->addAttributeToFilter('entity_id', array('nin' => $this->_getSentIds()))
            ->addAttributeToFilter('created_at', array('from' => $from, 'to' => $to))
            ->load();
         
        if (!$shipments) {
            return false;
        }
        return $shipments->getAllIds();
      }
      
      /**
       * sending mail and saves entry to db
       * 
       * @param array $shippmentIds shippmentId
       *
       * @return void
       */
       private function _sendTrustedRatingMails($shippmentIds) 
       {
           foreach ($shippmentIds as $shipmentId) {
               $orderId = $this->_getOrderId($shipmentId);
               $customerEmail = $this->_getCustomerEmail($shipmentId);
               
               $this->_sendTransactionalMail($orderId, $customerEmail);
               $this->_saveShippmentIdToTable($shipmentId);
           }
       }
        
        /**
         * sending Transactional Email
         * 
         * @param int    $orderId       Order Id
         * @param string $customerEmail Customer Email
         *
         * @return void
         */
         private function _sendTransactionalMail($orderId, $customerEmail) 
         {
             $mailTemplate = Mage::getModel('core/email_template');
             $template = Mage::getStoreConfig(self::XML_PATH_SYMMETRICS_TRUSTEDRATING_EMAIL_TEMPLATE);
             
             $mailTemplate->sendTransactional(
                 $template,
                 Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY),
                 $customerEmail, //replace through your own when you want to test it
                 $customerEmail,
                 array('emailwidget' => $this->_getEmailWidgetLink($orderId, $customerEmail))      
             );
         }
        /**
         * gets Customer Email by Shippment Id
         * 
         * @param int $shipmentId Shippment Id
         *
         * @return string
         */
         private function _getCustomerEmail($shipmentId) 
         {
             $shipment = Mage::getModel('sales/order_shipment')->load($shipmentId);
             $customerId = $shipment->getData('customer_id');
             $customer = Mage::getModel('customer/customer')->load($customerId);
             
             return $customer->getData('email');
         }
         
         /**
          * get Order id by Shippment Id
          * 
          * @param int $shipmentId Shippment Id
          *
          * @return int
          */
          private function _getOrderId($shipmentId) 
          {
              $shipment = Mage::getModel('sales/order_shipment')->load($shipmentId);
              
              return $shipment->getData('order_id');
          }
       
       /**
        * gets email widget
        * 
        * @param int    $orderId       Order Id
        * @param string $customerEmail Customer Email
        *
        * @return string
        */
        private function _getEmailWidgetLink($orderId, $customerEmail) 
        {
            $model = Mage::getModel('trustedrating/trustedrating');
            
            $buyerEmail = base64_encode($customerEmail);
            $orderId = base64_encode($orderId);
            $baseUrl = Mage::getBaseUrl('web');
            $link = '<a href="' . $model->getEmailRatingLink() . '_' . $model->getTsId() . '.html';
            $params = '&buyerEmail=' . $buyerEmail . '&shopOrderID=' . $orderId . '">';
            $widgetPath = Symmetrics_TrustedRating_Model_Trustedrating::IMAGE_LOCAL_PATH;
            $widget = '<img src="' . $baseUrl . $widgetPath . $model->getRatingLinkData('emailratingimage') . '"/></a>';
            
            return $link . $params . $widget;
        }
       
       /**
        * saves shipping-id to table who gots an email
        * 
        * @param int $shipmentId Shipping Id
        *
        * @return void
        */
        private function _saveShippmentIdToTable($shipmentId) 
        {
            $write = Mage::getSingleton('core/resource')->getConnection('core_write');
            $table = $this->getTable('symmetrics_trustedrating_emails');
            $sql = 'INSERT INTO ' . $table . ' (shippment_id) VALUES (' . $shipmentId . ');';
            $write->query($sql);
        }
       
       /**
        * get all ids from trusted_rating - table who gots an email
        * 
        * @return array
        */
        private function _getSentIds() 
        {
            $read = Mage::getSingleton('core/resource')->getConnection('core_read');
            $table = $this->getTable('symmetrics_trustedrating_emails');
            $sql = 'SELECT shippment_id FROM ' . $table . ';';
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
         * subs the days in the config (3 for default) from the actually date und returns the diff
         * 
         * @return array
         */
         private function _getDayInterval() 
         {
             $from = '1970-01-01 00:00:00';
             
             if (!$dayInterval = Mage::getStoreConfig('trustedrating/trustedrating_email/days')) {
                 return false;
             }
             $intervalSeconds = $dayInterval * 24 * 60 * 60;
             $date = new Zend_Date();
             $timestamp = $date->get();
             
             $diff = $timestamp - $intervalSeconds;
             
             return array(
                'from' => $from,
                'to' => date("Y-m-d H:i:s", $diff)
             );
         }
    
        /**
         * collect the data for sending to trusted rating
         *
         * @param int $storeId storeID
         *
         * @return array
         */ 
        private function _getSendData($storeId) 
        {
            $sendData = array();
        
            $sendData['tsId'] = Mage::getStoreConfig('trustedrating/data/trustedrating_id', $storeId);
            $sendData['activation'] = Mage::getStoreConfig('trustedrating/status/trustedrating_active', $storeId);
            $sendData['wsUser'] = Mage::helper('trustedrating')->getConfig('soapapi', 'wsuser');
            $sendData['wsPassword'] = Mage::helper('trustedrating')->getConfig('soapapi', 'wspassword');
            $sendData['partnerPackage'] = Mage::helper('trustedrating')->getConfig('soapapi', 'partnerpackage');
         
            return $sendData;   
        }
    
        /**
         * calling the soap api from trusted rating
         * 
         * @param array $sendData data to send
         *
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
}