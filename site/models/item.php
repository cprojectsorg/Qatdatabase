<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.modelitem');
class QatDatabaseModelItem extends JModelItem {
	protected $item;
	public function getItem() {
		if(!isset($this->item)) {
			$db = JFactory::getDBO();
			$jinput = JFactory::getApplication()->input;
			$id = $jinput->get('id', '', 'INT');
			if($id !== '') {
				$query = $db->getQuery(true);
				$query->select('*')->from('#__qatdatabase_items')->where('id=' . $id);
				$db->setQuery((string)$query);
				$this->item = $db->loadObject();
			} else {
				$application = JFactory::getApplication();
				$application->enqueueMessage('Empty id', 'error');
			}
		}
		
		return $this->item;
	}
}
