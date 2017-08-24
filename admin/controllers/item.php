<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

use Joomla\Utilities\ArrayHelper;

jimport('joomla.application.component.controllerform');

class QatDatabaseControllerItem extends JControllerForm {
	public function getModel($name = 'Item', $prefix = '', $config = array('ignore_request' => true)) {
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
	
	protected function allowAdd($data = array()) {
		$categoryId = ArrayHelper::getValue($data, 'catid', $this->input->getInt('filter_category_id'), 'int');
		$allow = null;
		
		if($categoryId) {
			// If the category has been passed in the data or URL check it.
			$allow = JFactory::getUser()->authorise('core.create', 'com_qatdatabase.category.' . $categoryId);
		}
		
		if($allow === null) {
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd();
		}
		
		return $allow;
	}
	
	protected function allowEdit($data = array(), $key = 'id') {
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user = JFactory::getUser();
		
		// Zero record (id:0), return component edit permission by calling parent controller method.
		if(!$recordId) {
			return parent::allowEdit($data, $key);
		}
		
		// Check edit on the record asset (explicit or inherited).
		if($user->authorise('core.edit', 'com_qatdatabase.item.' . $recordId)) {
			return true;
		}
		
		// Check edit own on the record asset (explicit or inherited).
		if($user->authorise('core.edit.own', 'com_qatdatabase.item.' . $recordId)) {
			// Existing record already has an owner, get it.
			$record = $this->getModel()->getItem($recordId);
			
			if(empty($record)) {
				return false;
			}
			
			// Grant if current user is owner of the record.
			return $user->id == $record->created_by;
		}
		
		return false;
	}
	
	public function save($key = null, $urlVar = null) {
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$jinput = JFactory::getApplication()->input;
		$data = $jinput->post->getArray();
		
		$model = $this->getModel();
		$IsNew = ($model->getItem()->id == 0);
		
		$user = JFactory::getUser();
		$userId = $user->id;
		
		$app = JFactory::getApplication();
		
		// If new.
		if($IsNew) {
			if($user->authorise('core.create', 'com_qatdatabase')) {
				$auth = true;
			} else {
				$auth = false;
			}
		} else {
			// If exist.
			if($user->authorise('core.edit', 'com_qatdatabase') || ($user->authorise('core.edit.own', 'com_qatdatabase') && $model->getItem()->created_by == $userId)) {
				$auth = true;
			} else {
				$auth = false;
			}
		}
		
		if($auth == true) {
			if($model->save($data)) {
				if($this->task == 'save') {
					$app->Redirect(JRoute::_('index.php?option=com_qatdatabase&view=items', false));
				} elseif($this->task == 'apply') {
					$app->Redirect(JRoute::_('index.php?option=com_qatdatabase&view=item&layout=edit&id=' . $model->postSaveHook(), false));
				}
			}
		} else {
			$app->enqueueMessage(JText::_('COM_QATDATABASE_AUTH_NOT_PERMITTED'), 'error');
			$app->Redirect(JRoute::_('index.php?option=com_qatdatabase&view=items', false));
		}
		
		return true;
	}
}
