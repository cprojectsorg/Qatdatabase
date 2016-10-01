<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

class QatDatabaseViewEdit extends JViewLegacy {
	public function display($tpl = null) {
		$jinput = JFactory::getApplication()->input;
		$id = $jinput->get('id', '', 'INT');
		if($id !== '') {
			echo $id;
		}
		
		parent::display($tpl);
	}
}
