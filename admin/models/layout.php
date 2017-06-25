<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

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
	
	protected function InsertNewFieldOrder($ordering, $fieldid) {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$cols = array('id', 'ordering', 'fieldid');
		$vals = implode(',', array($db->quote(''), $db->quote($ordering), $db->quote($fieldid)));
		
		$query->insert('#__qatdatabase_fields_ordering')->columns($cols)->values($vals);
		
		$db->setQuery($query);
		
		if($db->execute()) {
			return true;
		}
		
		return false;
	}
	
	public function CheckTheNewFields() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$rquery = $db->getQuery(true);
		
		$LastOrder = count($this->GetFieldsOrdering(false));
		$Order = 0;
		$Order += $LastOrder + 1;
		
		$query->select('*')->from('#__qatdatabase_fields_ordering');
		$rquery->select('*')->from('#__qatdatabase_fields')->where('published IN (0,1)');
		
		$exfields = $db->setQuery($query)->loadObjectList();
		$fields = $db->setQuery($rquery)->loadObjectList();
		
		$insert = array();
		
		foreach($exfields as $exfield) {
			$insert[$exfield->fieldid] = 'false';
		}
		
		foreach($fields as $field) {
			if(!isset($insert[$field->id])) {
				$this->InsertNewFieldOrder($Order, $field->id);
				$Order++;
			}
		}
		
		return true;
	}
	
	public function GetFieldsOrdering($doInsert = true) {
		$db = JFactory::getDBO();
		
		$query = $db->getQuery(true);
		$query->select('field.id, field.published, field.title, field.name, field.required, field.editable, field.description, field.type, field.catid')->from('#__qatdatabase_fields AS field');
		
		if($this->IsTableEmpty('#__qatdatabase_fields_ordering') == 'false') {
			$query->join('RIGHT', '#__qatdatabase_fields_ordering AS fieldorder ON field.id = fieldorder.fieldid')->order('fieldorder.ordering ASC');
		}
		
		$query->where('field.published IN (0,1)');
		
		if($doInsert == true) {
			$this->CheckTheNewFields();
		}
		
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}
