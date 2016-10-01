<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.view');
class QatDatabaseViewFields extends JViewLegacy {
	function display($tpl = null) {
		$app = JFactory::getApplication();
		$context = "qatdatabase.list.admin.qatdatabase";
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->model = $this->getModel('Fields');
		$this->filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'title', 'cmd');
		$this->filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd');
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->addToolBar();
		
		if(count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		$this->SideBarMenus = JHtmlSidebar::render();
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		$document = JFactory::getDocument();
		
		if($this->pagination->total == '0') {
			$total = '';
		} else {
			$total = '(' . $this->pagination->total . ')';
		}
		
		$title = JText::_('COM_QATDATABASE') . ': ' . JText::_('COM_QATDATABASE_FIELDS');
		$title .= "<span style='font-size: 0.6em; font-weight: bold; vertical-align: middle;'> " . $total . "</span>";
		
		JToolBarHelper::title($title, 'database');
		
		JToolBarHelper::addNew('field.add');
		JToolBarHelper::editList('field.edit');
		JToolBarHelper::publish('fields.publish', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('fields.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::archiveList('fields.archive');
		
		JToolbarHelper::custom('fields.required', 'pin red', '', 'COM_QATDATABASE_FIELD_REQUIRED', true);
		JToolbarHelper::custom('fields.notrequired', 'pin', '', 'COM_QATDATABASE_FIELD_NOT_REQUIRED', true);
		JToolbarHelper::custom('fields.editable', 'pencil blue', '', 'COM_QATDATABASE_FIELD_EDITABLE', true);
		JToolbarHelper::custom('fields.noteditable', 'pencil', '', 'COM_QATDATABASE_FIELD_NOT_EDITABLE', true);
		
		$layout = new JLayoutFile('joomla.toolbar.batch');
		
		JToolbar::getInstance('toolbar')->appendButton('Custom', $layout->render(array('title' => JText::_('JTOOLBAR_BATCH'))), 'batch');
		
		if($this->state->get('filter.published') == -2) {
			JToolBarHelper::deleteList('', 'fields.delete', 'JTOOLBAR_EMPTY_TRASH');
		} else {
			JToolBarHelper::trash('fields.trash');
		}
		
		JToolbarHelper::preferences('com_qatdatabase');
	}
}
