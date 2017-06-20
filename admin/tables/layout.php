<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.database.table');

class QatDatabaseTableLayout extends JTable {
	function __construct(&$db) {
		parent::__construct('#__qatdatabase_fields_ordering', 'id', $db);
	}
}
