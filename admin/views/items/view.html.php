<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.view');

class QatDatabaseViewItems extends JViewLegacy {
	function display($tpl = null) {
		$app = JFactory::getApplication();
		$context = "qatdatabase.list.admin.qatdatabase";
		$this->model = $this->getModel();
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->canDo = JHelperContent::getActions('com_qatdatabase');
		$this->filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'id', 'cmd');
		$this->filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'desc', 'cmd');
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
		$canDo = $this->canDo;
		$user = JFactory::getUser();
		$userId = $user->id;
		
		if($this->pagination->total == '0') {
			$total = '';
		} else {
			$total = '(' . $this->pagination->total . ')';
		}
		
		$title = JText::_('COM_QATDATABASE') . ': ' . JText::_('COM_QATDATABASE_ITEMS');
		$title .= "<span style='font-size: 0.6em; font-weight: bold; vertical-align: middle;'> " . $total . "</span>";
		
		JToolBarHelper::title($title, 'database');
		
		if($canDo->get('core.create')) {
			JToolBarHelper::addNew('item.add');
		}
		
		JToolBarHelper::editList('item.edit');
		
		if($canDo->get('core.edit.state')) {
			JToolBarHelper::publish('items.publish', 'JTOOLBAR_PUBLISH', true);
			JToolBarHelper::unpublish('items.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolBarHelper::archiveList('items.archive');
			
			if($this->state->get('filter.published') == -2) {
				JToolBarHelper::deleteList('', 'items.delete', 'JTOOLBAR_EMPTY_TRASH');
			} else {
				JToolBarHelper::trash('items.trash');
			}
		}
		
		if($canDo->get('core.admin')) {
			JToolbarHelper::preferences('com_qatdatabase');
		}
	}
}
?>