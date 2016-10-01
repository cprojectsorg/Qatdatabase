<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.controllerform');
class QatDatabaseControllerField extends JControllerForm {
	public function batch($model = null) {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$model = $this->getModel('Field', '', array());
		$this->setRedirect(JRoute::_('index.php?option=com_qatdatabase&view=fields' . $this->getRedirectToListAppend(), false));
		
		return parent::batch($model);
	}
}
