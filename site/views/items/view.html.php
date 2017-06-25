<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
class QatDatabaseViewItems extends JViewLegacy {
	public function display($tpl = null) {
		$this->items = $this->get('Items');
		$this->config = JComponentHelper::getParams('com_qatdatabase');
		$this->cssPrefix = JText::_('COM_QATDATABASE_CSS_PREFIX');
		
		if(count($errors = $this->get('Errors'))) {
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
			return false;
		}
		
		parent::display($tpl);
	}
}
