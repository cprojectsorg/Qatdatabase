<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
class QatDatabaseController extends JControllerLegacy {
	public function display($cachable = false, $urlparams = false) {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->getCmd('view', 'Items'));
		parent::display();
	}
}
?>
