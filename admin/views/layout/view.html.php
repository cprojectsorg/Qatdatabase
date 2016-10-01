<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.view');
class QatDatabaseViewLayout extends JViewLegacy {
	function display($tpl = null) {
		if(count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		$this->SideBarMenus = JHtmlSidebar::render();
		$this->addToolBar();
		
		$layoutInput = JFactory::getApplication()->input->get('layout', 'nolayout', '');
		
		if($layoutInput == 'nolayout') {
			JFactory::getApplication()->redirect('index.php?option=com_qatdatabase&view=layout&layout=select');
		}
		
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		$input = JFactory::getApplication()->input;
		$layout = $input->get('layout', '', '');
		
		$Title = '';
		
		if($layout == 'select') {
			$Title = ' - ' . JText::_('COM_QATDATABASE_LAYOUT_SELECT');
		}
		
		if($layout == 'postform') {
			$Title = ' - ' . JText::_('COM_QATDATABASE_POST_FORM_LAYOUT');
		}
		
		if($layout == 'itemslist') {
			$Title = ' - ' . JText::_('COM_QATDATABASE_ITEMS_LIST_LAYOUT');
		}
		
		if($layout == 'item') {
			$Title = ' - ' . JText::_('COM_QATDATABASE_ITEM_LAYOUT');
		}
		
		$title = JText::_('COM_QATDATABASE') . ': ' . JText::_('COM_QATDATABASE_LAYOUT_VIEW_TITLE') . $Title;
		
		JToolBarHelper::title($title, 'database');
	}
}
?>
