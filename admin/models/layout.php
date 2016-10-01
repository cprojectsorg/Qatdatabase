<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.modellist');
class QatDatabaseModelLayout extends JModelList {
	public function __construct($config = array()) {
		
		parent::__construct($config);
	}
	
	public function GetFieldsOrdering() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('field.id, field.published, field.title, field.name, field.required, field.editable, field.description, field.type, field.catid')->from('#__qatdatabase_fields AS field');
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}
