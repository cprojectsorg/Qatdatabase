<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
class QatDatabaseViewItem extends JViewLegacy {
	public function display($tpl = null) {
		$this->item = $this->get('Item');
		$this->cssPrefix = JText::_('COM_QATDATABASE_CSS_PREFIX');
		if(count($errors = $this->get('Errors'))) {
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
			return false;
		}
		parent::display($tpl);
	}
}
