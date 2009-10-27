<?php
/**
 * Symmetrics_TrustedRating_Admin_RegistrationController
 *
 * @category Symmetrics
 * @package Symmetrics_TrustedRating
 * @author symmetrics gmbh <info@symmetrics.de>, Siegfried Schmitz <ss@symmetrics.de>
 * @copyright symmetrics gmbh
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Symmetrics_TrustedRating_Admin_RegistrationController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
		$model = Mage::getModel('trustedrating/trustedrating');
		$this->getResponse()->setRedirect($model->getRegistrationLink());
    }
}