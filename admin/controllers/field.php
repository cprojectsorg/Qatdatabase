<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

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
