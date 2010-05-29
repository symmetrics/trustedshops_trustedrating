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
     * Config path to email template
     *
     * @var string
     */
    const XML_PATH_SYMMETRICS_TRUSTEDRATING_EMAIL_TEMPLATE = 'sales_email/trustedrating/template';

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
     * @param Varien_Event_Observer $observer Observer
     *
     * @return void
     */
    public function checkSendRatingEmail($observer)
    {
        if ($this->isActive() && $shipmentIds = $this->_checkShippings()) {
            $this->_sendTrustedRatingMails($shipmentIds);
        }
    }

    /**
     * Get all shippings which are older than x days and are not in table
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

        $shipments = Mage::getResourceModel('sales/order_shipment_collection');
        if ($sentIds = $this->_getSentIds()) {
            if (!is_null($sentIds)) {
                $shipments->addAttributeToFilter('entity_id', array('nin' => $sentIds));
            }
        }
        $shipments->addAttributeToFilter('created_at', array('from' => $from, 'to' => $to))
            ->load();

        if (!$shipments) {
            return false;
        }
        return $shipments->getAllIds();
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
           $orderId = $this->_getOrderId($shipmentId);
           $customerEmail = $this->_getCustomerEmail($shipmentId);

           $this->_sendTransactionalMail($orderId, $customerEmail);
           $this->_saveShipmentIdToTable($shipmentId);
       }
    }

    /**
     * Send transactional email
     *
     * @param int    $orderId       Order Id
     * @param string $customerEmail Customer Email
     *
     * @return void
     */
    private function _sendTransactionalMail($orderId, $customerEmail)
    {
        $orderStoreId = Mage::getModel('sales/order')->load($orderId)->getStoreId();
        $countryCode = substr(Mage::getStoreConfig('general/locale/code', $orderStoreId), 0, 2);

        $templateConfigPath = self::XML_PATH_SYMMETRICS_TRUSTEDRATING_EMAIL_TEMPLATE . '/' . $countryCode;
        $template = Mage::getStoreConfig($templateConfigPath, $orderStoreId);
        $emailWidgetLink = $this->_getEmailWidgetLink($orderId, $customerEmail, $orderStoreId);

        Mage::getModel('core/email_template')->sendTransactional(
            $template,
            Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY),
            $customerEmail, //replace through your own when you want to test it
            $customerEmail,
            array('emailwidget' => $emailWidgetLink),
            $orderStoreId
        );
    }

    /**
     * Get customer email by shipment Id
     *
     * @param int $shipmentId Shipment Id
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
     * Get order ID by shipment ID
     *
     * @param int $shipmentId Shipment Id
     *
     * @return int
     */
    private function _getOrderId($shipmentId)
    {
        $shipment = Mage::getModel('sales/order_shipment')->load($shipmentId);
        return $shipment->getData('order_id');
    }

    /**
     * Generate email widget code
     *
     * @param int    $orderId       Order Id
     * @param string $customerEmail Customer Email
     * @param int    $orderStoreId  Order store id, used for multistore logic
     *
     * @return string
     */
    private function _getEmailWidgetLink($orderId, $customerEmail, $orderStoreId)
    {
        $model = Mage::getModel('trustedrating/trustedrating');

        $buyerEmail = base64_encode($customerEmail);
        $orderId = base64_encode($orderId);
        $baseUrl = Mage::getBaseUrl('web');
        $link = '<a href="' . $model->getEmailRatingLink() . '_' . $model->getTsId() . '.html';
        $params = '&buyerEmail=' . $buyerEmail . '&shopOrderID=' . $orderId . '">';
        $widgetPath = Symmetrics_TrustedRating_Model_Trustedrating::IMAGE_LOCAL_PATH;
        $ratingLinkData = $model->getRatingLinkData('emailratingimage', $orderStoreId);
        $widget = '<img src="' . $baseUrl . $widgetPath . $ratingLinkData . '"/></a>';

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
     * Get all IDs from trusted_rating table of customers which already got an email
     *
     * @return array
     */
     private function _getSentIds()
     {
         $mailModel = Mage::getModel('trustedrating/mail');
         $shipmentIds = array();
         $model = $mailModel->getCollection();
         $items = $model->getItems();
         foreach ($items as $item) {
             $shipmentIds[] = $item->getShippmentId();
         }

         return $shipmentIds;
     }

    /**
     * Substract the days in the config (3 for default) from the current date for upper limit
     * and get the "include since" date (default: setup date) for lower limit; return both in array
     *
     * @return array
     */
    private function _getDayInterval()
    {
        $from = $this->getHelper()->getActiveSince();
        $fromString = $from->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        $dayInterval = (float) Mage::getStoreConfig('trustedrating/trustedrating_email/days');
        if (is_null($dayInterval) || $dayInterval < 0) {
            return false;
        }
        
        $intervalSeconds = $dayInterval * 24 * 60 * 60;
        $date = new Zend_Date();
        $timestamp = $date->get();
        
        $diff = $timestamp - $intervalSeconds;

        return array(
            'from' => $fromString,
            'to' => date("Y-m-d H:i:s", $diff)
        );
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
