<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.view');

class QatDatabaseViewField extends JViewLegacy {
	protected $form = null;
	
	public function display($tpl = null) {
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->script = $this->get('Script');
		$this->document = JFactory::getDocument();
		
		if(count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		$this->FormPref = 'jform';
		$this->addToolBar();
		parent::display($tpl);
		$this->setDocument();
	}
	
	protected function addToolBar() {
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);
		$isNew = ($this->item->id == 0);
		if($isNew) {
			$title = JText::_('COM_QATDATABASE') . ': ' . JText::_('COM_QATDATABASE_FIELDS') . ' - ' . JText::_('COM_QATDATABASE_FIELD_NEW');
		} else {
			$title = JText::_('COM_QATDATABASE') . ': ' . JText::_('COM_QATDATABASE_FIELDS') . ' - ' . JText::_('COM_QATDATABASE_FIELD_EDIT');
		}
		JToolBarHelper::title($title, 'database');
		JToolBarHelper::apply('field.apply');
		JToolBarHelper::save('field.save');
		JToolbarHelper::save2new('field.save2new');
		JToolBarHelper::cancel('field.cancel', $isNew ? 'JTOOLBAR_CANCEL':'JTOOLBAR_CLOSE');
	}
	
	protected function setDocument() {
		$document = JFactory::getDocument();
		$document->addScript(JURI::root() . $this->script);
		$document->addScript(JURI::root() . "/administrator/components/com_qatdatabase/views/field/submitbutton.js");
		
		JText::script('COM_QATDATABASE_TYPE');
		
		JText::script('COM_QATDATABASE_FIELD_NAME');
		JText::script('COM_QATDATABASE_FIELD_ROWS');
		JText::script('COM_QATDATABASE_FIELD_VALUE');
		JText::script('COM_QATDATABASE_FIELD_EDITOR');
		JText::script('COM_QATDATABASE_FIELD_REMOVE');
		JText::script('COM_QATDATABASE_FIELD_COLUMNS');
		JText::script('COM_QATDATABASE_FIELD_MOVE_UP');
		JText::script('COM_QATDATABASE_FIELD_TEXTAREA');
		JText::script('COM_QATDATABASE_FIELD_ADD_MORE');
		JText::script('COM_QATDATABASE_FIELD_MOVE_DOWN');
		JText::script('COM_QATDATABASE_FIELD_LINK_TEXT');
		JText::script('COM_QATDATABASE_FIELD_MAX_LENGTH');
		JText::script('COM_QATDATABASE_FIELD_CALENDAR_STYLE');
		JText::script('COM_QATDATABASE_FIELD_CURRENCY_SYMBOL');
		JText::script('COM_QATDATABASE_FIELD_CHECKBOX_AUTO_CHECK');
		JText::script('COM_QATDATABASE_FIELD_CALENDAR_STYLE_JOOMLA');
		JText::script('COM_QATDATABASE_FIELD_CALENDAR_STYLE_DEFAULT');
		
		JText::script('COM_QATDATABASE_ERROR_FIELD_FIELD_ERROR');
	}
}
