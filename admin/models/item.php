<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.modeladmin');

class QatDatabaseModelItem extends JModelAdmin {
	public function getTable($type = 'Item', $prefix = 'QatdatabaseTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true) {
		$form = $this->loadForm('com_qatdatabase.Item', 'Item', array('control' => 'jform', 'load_data' => $loadData));
		if(empty($form)) {
			return false;
		}
		
		return $form;
	}
	
	public function getScript() {
		return 'administrator/components/com_qatdatabase/models/forms/item.js';
	}
	
	protected function loadFormData() {
		$data = JFactory::getApplication()->getUserState('com_qatdatabase.edit.item.data', array());
		if(empty($data)) {
			$data = $this->getItem();
		}
		
		return $data;
	}
	
	public function save($data) {
		if($data['alias'] == null) {
			if(JFactory::getConfig()->get('unicodeslugs') == 1) {
				$data['alias'] = JFilterOutput::stringURLUnicodeSlug($data['title']);
			} else {
				$data['alias'] = JFilterOutput::stringURLSafe($data['title']);
			}
			
			list($title, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
			$data['alias'] = $alias;
		}
		
		if(parent::save($data)) {
			return true;
		}
		
		return false;
	}
}
?>
