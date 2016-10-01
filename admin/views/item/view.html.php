<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.view');
class QatDatabaseViewItem extends JViewLegacy {
	protected $form = null;
	
	public function display($tpl = null) {
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->script = $this->get('Script');
		if(count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		$this->addToolBar();
		parent::display($tpl);
		$this->setDocument();
	}
	
	protected function addToolBar() {
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);
		$isNew = ($this->item->id == 0);
		if($isNew) {
			$title = JText::_('COM_QATDATABASE') . ': ' . JText::_('COM_QATDATABASE_ITEMS') . ' - ' . JText::_('COM_QATDATABASE_ITEM_NEW');
		} else {
			$title = JText::_('COM_QATDATABASE') . ': ' . JText::_('COM_QATDATABASE_ITEMS') . ' - ' . JText::_('COM_QATDATABASE_ITEM_EDIT');
		}
		JToolBarHelper::title($title, 'database');
		JToolBarHelper::apply('item.apply');
		JToolBarHelper::save('item.save');
		JToolbarHelper::save2new('item.save2new');
		JToolBarHelper::cancel('item.cancel', $isNew ? 'JTOOLBAR_CANCEL':'JTOOLBAR_CLOSE');
	}
	
	protected function setDocument() {
		$document = JFactory::getDocument();
		$document->addScript(JURI::root() . $this->script);
		$document->addScript(JURI::root() . "/administrator/components/com_qatdatabase/views/item/submitbutton.js");
		
		JText::script('COM_QATDATABASE_ERROR_ITEM_FIELD_ERROR');
	}
}
?>
