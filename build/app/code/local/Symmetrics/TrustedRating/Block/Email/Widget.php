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
  * Symmetrics_TrustedRating_Block_Email_Widget
  *
  * @category  Symmetrics
  * @package   Symmetrics_TrustedRating
  * @author    symmetrics gmbh <info@symmetrics.de>
  * @author    Siegfried Schmitz <ss@symmetrics.de>
  * @copyright 2009 Symmetrics Gmbh
  * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
  * @link      http://www.symmetrics.de/
  */
class Symmetrics_TrustedRating_Block_Email_Widget extends Symmetrics_TrustedRating_Block_Widget_Abstract
{
    /**
     * returns the email widget 
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($data = $this->getDataForWidget('EMAIL')) {
            $buyerEmail = base64_encode($data['buyerEmail']);
            $baseUrl = Mage::getBaseUrl('web');
            $orderId = base64_encode($data['orderId']);
            $link = '<a target="_blank" href="' . $data['ratingLink'] . '_' . $data['tsId'] . '.html';
            $params = '&buyerEmail=' . $buyerEmail . '&shopOrderID=' . $orderId . '">';
            $widgetSrc = $baseUrl . $data['imageLocalPath'] . $data['widgetName'];
            $widget = '<img border="0" alt="" src="' . $widgetSrc . '"/></a>';
            
            return $link . $params . $widget;
        } else {
            return null;
        }
    }
}