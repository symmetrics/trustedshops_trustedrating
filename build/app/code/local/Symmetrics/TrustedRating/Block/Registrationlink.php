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
class Symmetrics_TrustedRating_Block_Registrationlink extends Symmetrics_TrustedRating_Block_Widget_Abstract
{
    /**
     * return the Trusted Rating Registration Link and the js-function to put it into the comment-field
     * 
     * @return string
     */
    protected function _toHtml()
    {   
        if (!strpos($_SERVER['PHP_SELF'], 'section/trustedrating')) {
            return null;
        }
        $languageLabel = $this->getLanguageLabel();
        $registrationlinkData = $this->getRegistrationLink();
        $target = $registrationlinkData['target'];
        $text = $registrationlinkData['text'];

        $link = '<a href = "' . $target . '" target = "_blank">' . $text . '</a>';

        $registrationLink = '<script type="text/javascript">
            document.observe(\'dom:loaded\', function() {
                var comment = $$(\'#trustedrating_trustedrating_registration div\')[0];
                comment.innerHTML = \'' . $link . '\';
                var languageLabel = $$(\'#trustedrating_data label\')[1];
                languageLabel.innerHTML = \'' . $languageLabel . '\';
            });
        </script>';
        
        return $registrationLink;
    }
}