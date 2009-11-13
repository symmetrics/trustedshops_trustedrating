<?php
/**
 * Symmetrics_TrustedRating_Block_Widget
 *
 * @category Symmetrics
 * @package Symmetrics_TrustedRating
 * @author symmetrics gmbh <info@symmetrics.de>, Siegfried Schmitz <ss@symmetrics.de>
 * @copyright symmetrics gmbh
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Symmetrics_TrustedRating_Block_Widget extends Symmetrics_TrustedRating_Block_Widget_Abstract
{
    /**
     * returns the widget 
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($data = $this->getDataForWidget('RATING')) {
            $baseUrl = Mage::getBaseUrl('web');
            $link = '<a href="' . $data['ratingLink'] . '_' . $data['tsId'] . '.html">';
            $widgetSrc = $baseUrl . $data['imageLocalPath'] . $data['tsId'] . '.gif';
            $widget = '<img alt="" border="0" src="' . $widgetSrc .  '" /></a>';
            return $link . $widget;
        } else {
            return '';
        }
    }
}