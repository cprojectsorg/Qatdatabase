<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.database.table');

class QatDatabaseTableField extends JTable {
	function __construct(&$db) {
		parent::__construct('#__qatdatabase_fields', 'id', $db);
	}
	
	public function bind($array, $ignore = '') {
		if(is_array($array['catid'])) {
			$array['catid'] = implode(',', $array['catid']);
		}
		
		if(is_array($array['names'])) {
			$array['names'] = implode(',', $array['names']);
		}
		
		if(is_array($array['values'])) {
			$array['values'] = implode(',', $array['values']);
		}
		
		if(is_array($array['parameters'])) {
			$array['parameters'] = implode(',', $array['parameters']);
		}
		
		return parent::bind($array, $ignore);
	}
}