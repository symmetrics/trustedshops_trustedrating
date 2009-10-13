<?php
class Symmetrics_TrustedRating_Block_Widget extends Mage_Core_Block_Template
{

    protected function _construct()
    {
        parent::_construct();
    }

    protected function _toHtml()
    {
    	$model = new Symmetrics_TrustedRating_Model_TrustedRating();
		if($model->getIsActive()) {
			return $model->getImage();
		}
    	else {
    		return '';
    	}
    }
}