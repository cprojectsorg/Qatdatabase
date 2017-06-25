<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.controllerform');

JLoader::register('QatDatabaseControllerItem', JPATH_ADMINISTRATOR . '/components/com_qatdatabase/controllers/item.php');

class QatDatabaseControllerEdit extends QatDatabaseControllerItem {
	public function getModel($name = 'Edit', $prefix = '', $config = array('ignore_request' => true)) {
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
	
	public function save($key = null, $urlVar = null) {
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$jinput = JFactory::getApplication()->input;
		$data = $jinput->post->getArray();
		
		$model = $this->getModel();
		$SaveItemProcess = $model->save($data);
		
		$app = JFactory::getApplication();
		if($SaveItemProcess) {
			$app->enqueueMessage(JText::_('JLIB_APPLICATION_SAVE_SUCCESS'), 'message');
			$app->Redirect(JRoute::_('index.php?option=com_qatdatabase&view=edit'));
		} else {
			$app->enqueueMessage(JText::_('COM_QATDATABASE_ITEM_SAVE_ERROR'), 'error');
			$app->Redirect(JRoute::_('index.php?option=com_qatdatabase&view=edit'));
		}
		
		return true;
	}
}
