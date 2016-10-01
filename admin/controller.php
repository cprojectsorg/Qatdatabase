<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

class QatDatabaseController extends JControllerLegacy {
	function display($cachable = false, $urlparams = false) {
		require_once JPATH_COMPONENT . '/helpers/qatdatabase.php';
		QatdatabaseHelper::addSubmenu(JRequest::getCmd('view', 'items'));
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->getCmd('view', 'Items'));
		parent::display($cachable);
	}
}
?>
