<?php

class Symmetrics_TrustedRating_Model_System_Rating
{
    protected $_options = null;
    public function toOptionArray()
    {
        if (is_null($this->_options)) {
            $this->_options = array();
            $this->_options[0] = array(
                'value' => 'de',
                'label' => Mage::helper('trustedrating')->__('deutsch'),
            );
	        $this->_options[1] = array(
	            'value' => 'en',
	            'label' => Mage::helper('trustedrating')->__('englisch'),
	        );
		    $this->_options[2] = array(
		        'value' => 'fr',
		        'label' => Mage::helper('trustedrating')->__('französisch'),
		    );
			$this->_options[3] = array(
			    'value' => 'es',
			    'label' => Mage::helper('trustedrating')->__('spanisch'),
			);
		}
        return $this->_options;
    }
}