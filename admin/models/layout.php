<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.modellist');
class QatDatabaseModelLayout extends JModelList {
	public function __construct($config = array()) {
		parent::__construct($config);
	}
	
	protected function IsTableEmpty($Table) {
		$db = JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM `" . $Table . "`";
		$db->setQuery($query);
		$Result = $db->loadResult();
		
		if($Result == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	public function GetFieldsOrdering() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('field.id, field.published, field.title, field.name, field.required, field.editable, field.description, field.type, field.catid')->from('#__qatdatabase_fields AS field');
		
		if($this->IsTableEmpty('#__qatdatabase_fields_ordering') == 'false') {
			$query->join('RIGHT', '#__qatdatabase_fields_ordering AS fieldorder ON field.id = fieldorder.fieldid')->order('fieldorder.ordering ASC');
		}
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}
