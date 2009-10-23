<?php
/**
 * Symmetrics_TrustedRating_Model_System_Rating
 *
 * @category Symmetrics
 * @package Symmetrics_TrustedRating
 * @author symmetrics gmbh <info@symmetrics.de>, Siegfried Schmitz <ss@symmetrics.de>
 * @copyright symmetrics gmbh
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Symmetrics_TrustedRating_Model_System_Rating
{
	/**
     * option array
	 *
	 * @var array
     */
    protected $_options = array();

	/**
     * gets the languages for trusted rating site as option array
	 *
	 * @return array
     */
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