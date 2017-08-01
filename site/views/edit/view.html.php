<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

class QatDatabaseViewEdit extends JViewLegacy {
	public function display($tpl = null) {
		$this->item = $this->get('Item');
		$this->application = JFactory::getApplication();
		
		$jinput = JFactory::getApplication()->input;
		$id = $jinput->get('id', '', 'INT');
		
		if(count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		$this->setDocument();
		
		parent::display($tpl);
	}
	
	protected function setDocument() {
		$document = JFactory::getDocument();
		$document->addScript(JURI::root() . DS . "components" . DS . "com_qatdatabase" . DS . "views" . DS . "edit" . DS . "edit.js");
		
		JText::script('COM_QATDATABASE_ITEM_ADD_IMAGES');
		JText::script('COM_QATDATABASE_ITEM_IMAGE_SELECTED');
		JText::script('COM_QATDATABASE_ITEM_IMAGES_SELECTED');
		JText::script('COM_QATDATABASE_ERROR_ITEM_FIELD_ERROR');
	}
}