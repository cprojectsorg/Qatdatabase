<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

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
