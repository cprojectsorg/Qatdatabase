<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.modeladmin');

class QatDatabaseModelField extends JModelAdmin {
	public function getTable($type = 'Field', $prefix = 'QatdatabaseTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true) {
		$form = $this->loadForm('com_qatdatabase.Field', 'Field', array('control' => 'jform', 'load_data' => $loadData));
		if(empty($form)) {
			return false;
		}
		
		return $form;
	}
	
	public function getScript() {
		return 'administrator/components/com_qatdatabase/models/forms/field.js';
	}
	
	protected function loadFormData() {
		$data = JFactory::getApplication()->getUserState('com_qatdatabase.edit.field.data', array());
		if(empty($data)) {
			$data = $this->getItem();
			
			// Get Categories.
			$data->catid = explode(',', $data->catid);
		}
		
		return $data;
	}
	
	public function FieldTypeData() {
		$data = $this->getItem();
		if(empty($data)) {
			$data = $this->getItem();
		}
		
		return $data->type;
	}
	
	public function GetFieldsInputs($fieldID) {
		if(empty($data)) {
			$data = $this->getItem();
			
			$data->names = explode(',', $data->names);
			$data->values = explode(',', $data->values);
		}
		
		switch($fieldID) {
			case 1:
				$count = '0';
				$return = '<div id="paramscontr" class="paramscz control-group"><input type="button" onclick="AddMore(\'1\');" class="btn btn-success" value="' . JText::_('COM_QATDATABASE_FIELD_ADD_MORE') . '" /></div>';
				foreach($data->names as $name) {
					if($name !== '') {
						if($count == 0) {
							$return .= '<div class="control-group"><div title="' . JText::_('COM_QATDATABASE_FIELD_MOVE_UP') . '" onclick="Move(this, \'up\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-up"><span></span></div><div title="' . JText::_('COM_QATDATABASE_FIELD_MOVE_DOWN') . '" onclick="Move(this, \'down\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-down"><span></span></div><input title="' . JText::_('COM_QATDATABASE_FIELD_NAME') . '" class="hasTooltip field-params-input" placeholder="' . JText::_('COM_QATDATABASE_FIELD_NAME') . '" type="text" name="jform[names][]" value="' . $name . '" /><input title="' . JText::_('COM_QATDATABASE_FIELD_VALUE') . '" class="hasTooltip field-params-input" placeholder="' . JText::_('COM_QATDATABASE_FIELD_VALUE') . '" type="text" name="jform[values][]" value="' . $data->values[$count] . '" /></div>';
						} else {
							if($count > 0) {
								$return .= '<div id="params" class="paramsc' . $count . ' control-group"><div title="' . JText::_('COM_QATDATABASE_FIELD_MOVE_UP') . '" onclick="Move(this, \'up\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-up"><span></span></div><div title="' . JText::_('COM_QATDATABASE_FIELD_MOVE_DOWN') . '" onclick="Move(this, \'down\')" class="hasTooltip btn btn-info btn-small field-params-input field-button-down"><span></span></div><input title="' . JText::_('COM_QATDATABASE_FIELD_NAME') . '" class="hasTooltip field-params-input" placeholder="' . JText::_('COM_QATDATABASE_FIELD_NAME') . '" type="text" name="jform[names][]" value="' . $name . '" /><input title="' . JText::_('COM_QATDATABASE_FIELD_VALUE') . '" class="hasTooltip field-params-input" placeholder="' . JText::_('COM_QATDATABASE_FIELD_VALUE') . '" type="text" name="jform[values][]" value="' . $data->values[$count] . '" /><input onclick="removeField(this);" type="button" value="' . JText::_('COM_QATDATABASE_FIELD_REMOVE') . '" class="btn btn-small btn-danger" /></div>';
							}
						}
						$count++;
					}
				}
				return $return;
				break;
			
			case 2:
				break;
			
			case 3:
				if($data->parameters == '[jtype]') {
					$IfJtypeChecked = ' checked="checked"';
					$IfJtypeCheckedClass = ' btn active';
					$IfBtypeChecked = '';
					$IfBtypeCheckedClass = '';
				} elseif($data->parameters == '[btype]') {
					$IfJtypeChecked = '';
					$IfJtypeCheckedClass = '';
					$IfBtypeChecked = ' checked="checked"';
					$IfBtypeCheckedClass = ' btn active';
				}
				
				$return = '<div id="paramscontr" class="paramscz control-group"><div class="controls" style="margin: 0px !important;"><span>' . JText::_('COM_QATDATABASE_FIELD_CALENDAR_STYLE') . ': </span><fieldset class="radio btn-group btn-group-yesno"><input id="field-option-type-jtype"' . $IfJtypeChecked . ' value="[jtype]" type="radio" name="jform[parameters][]"><label id="field-option-caltype-jtype-label" onclick="this.addClass(\'active\'); this.addClass(\'btn-success\'); document.getElementById(\'field-option-caltype-btype-label\').removeClass(\'btn-success\'); document.getElementById(\'field-option-caltype-btype-label\').removeClass(\'active\');" class="btn' . $IfJtypeCheckedClass . '" for="field-option-type-jtype" aria-invalid="false">' . JText::_('COM_QATDATABASE_FIELD_CALENDAR_STYLE_JOOMLA') . '</label><input id="field-option-type-btype"' . $IfBtypeChecked . ' value="[btype]" type="radio" name="jform[parameters][]"><label onclick="this.addClass(\'active\'); this.addClass(\'btn-success\'); document.getElementById(\'field-option-caltype-jtype-label\').removeClass(\'btn-success\'); document.getElementById(\'field-option-caltype-jtype-label\').removeClass(\'active\');" id="field-option-caltype-btype-label" class="btn' . $IfBtypeCheckedClass . '" for="field-option-type-btype" aria-invalid="false">' . JText::_('COM_QATDATABASE_FIELD_CALENDAR_STYLE_DEFAULT') . '</label></fieldset></div></div>';
				return $return;
				break;
			
			case 4:
				$count = '0';
				$return = '<div id="paramscontr" class="paramscz control-group"><input type="button" onclick="AddMore(\'1\');" class="btn btn-success" value="' . JText::_('COM_QATDATABASE_FIELD_ADD_MORE') . '" /></div>';
				foreach($data->names as $name) {
					if($name !== '') {
						if($count == 0) {
							$return .= '<div class="control-group"><div title="' . JText::_('COM_QATDATABASE_FIELD_MOVE_UP') . '" onclick="Move(this, \'up\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-up"><span></span></div><div title="' . JText::_('COM_QATDATABASE_FIELD_MOVE_DOWN') . '" onclick="Move(this, \'down\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-down"><span></span></div><input title="' . JText::_('COM_QATDATABASE_FIELD_NAME') . '" class="hasTooltip field-params-input" placeholder="' . JText::_('COM_QATDATABASE_FIELD_NAME') . '" type="text" name="jform[names][]" value="' . $name . '" /><input title="' . JText::_('COM_QATDATABASE_FIELD_VALUE') . '" class="hasTooltip field-params-input" placeholder="' . JText::_('COM_QATDATABASE_FIELD_VALUE') . '" type="text" name="jform[values][]" value="' . $data->values[$count] . '" /></div>';
						} else {
							if($count > 0) {
								$return .= '<div id="params" class="paramsc' . $count . ' control-group"><div title="' . JText::_('COM_QATDATABASE_FIELD_MOVE_UP') . '" onclick="Move(this, \'up\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-up"><span></span></div><div title="' . JText::_('COM_QATDATABASE_FIELD_MOVE_DOWN') . '" onclick="Move(this, \'down\')" class="hasTooltip btn btn-info btn-small field-params-input field-button-down"><span></span></div><input title="' . JText::_('COM_QATDATABASE_FIELD_NAME') . '" class="hasTooltip field-params-input" placeholder="' . JText::_('COM_QATDATABASE_FIELD_NAME') . '" type="text" name="jform[names][]" value="' . $name . '" /><input title="' . JText::_('COM_QATDATABASE_FIELD_VALUE') . '" class="hasTooltip field-params-input" placeholder="' . JText::_('COM_QATDATABASE_FIELD_VALUE') . '" type="text" name="jform[values][]" value="' . $data->values[$count] . '" /><input onclick="removeField(this);" type="button" value="' . JText::_('COM_QATDATABASE_FIELD_REMOVE') . '" class="btn btn-small btn-danger" /></div>';
							}
						}
						$count++;
					}
				}
				return $return;
				break;
			
			case 5:
				$count = '0';
				$return = '<div id="paramscontr" class="paramscz control-group"><input type="button" onclick="AddMore(\'1\');" class="btn btn-success" value="' . JText::_('COM_QATDATABASE_FIELD_ADD_MORE') . '" /></div>';
				foreach($data->names as $name) {
					if($name !== '') {
						if($count == 0) {
							$return .= '<div class="control-group"><div title="' . JText::_('COM_QATDATABASE_FIELD_MOVE_UP') . '" onclick="Move(this, \'up\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-up"><span></span></div><div title="' . JText::_('COM_QATDATABASE_FIELD_MOVE_DOWN') . '" onclick="Move(this, \'down\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-down"><span></span></div><input title="' . JText::_('COM_QATDATABASE_FIELD_NAME') . '" class="hasTooltip field-params-input" placeholder="' . JText::_('COM_QATDATABASE_FIELD_NAME') . '" type="text" name="jform[names][]" value="' . $name . '" /><input title="' . JText::_('COM_QATDATABASE_FIELD_VALUE') . '" class="hasTooltip field-params-input" placeholder="' . JText::_('COM_QATDATABASE_FIELD_VALUE') . '" type="text" name="jform[values][]" value="' . $data->values[$count] . '" /></div>';
						} else {
							if($count > 0) {
								$return .= '<div id="params" class="paramsc' . $count . ' control-group"><div title="' . JText::_('COM_QATDATABASE_FIELD_MOVE_UP') . '" onclick="Move(this, \'up\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-up"><span></span></div><div title="' . JText::_('COM_QATDATABASE_FIELD_MOVE_DOWN') . '" onclick="Move(this, \'down\')" class="hasTooltip btn btn-info btn-small field-params-input field-button-down"><span></span></div><input title="' . JText::_('COM_QATDATABASE_FIELD_NAME') . '" class="hasTooltip field-params-input" placeholder="' . JText::_('COM_QATDATABASE_FIELD_NAME') . '" type="text" name="jform[names][]" value="' . $name . '" /><input title="' . JText::_('COM_QATDATABASE_FIELD_VALUE') . '" class="hasTooltip field-params-input" placeholder="' . JText::_('COM_QATDATABASE_FIELD_VALUE') . '" type="text" name="jform[values][]" value="' . $data->values[$count] . '" /><input onclick="removeField(this);" type="button" value="' . JText::_('COM_QATDATABASE_FIELD_REMOVE') . '" class="btn btn-small btn-danger" /></div>';
							}
						}
						$count++;
					}
				}
				return $return;
				break;
			
			case 6:
				$return = '<div><label class="paramslabel"><span>' . JText::_('COM_QATDATABASE_FIELD_MAX_LENGTH') . ': </span><input type="number" name="jform[max_length]" value="' . $data->max_length . '" /></label></div>';
				return $return;
				break;
			
			case 7:
				$return = '<div><label class="paramslabel"><span>' . JText::_('COM_QATDATABASE_FIELD_MAX_LENGTH') . ': </span><input type="number" name="jform[max_length]" value="' . $data->max_length . '" /></label></div>';
				return $return;
				break;
			
			case 8:
				$return = '<div class="control-group"><label class="paramslabel"><span>' . JText::_('COM_QATDATABASE_FIELD_MAX_LENGTH') . ': </span><input type="number" name="jform[max_length]" value="' . $data->max_length . '" /></label></div><div class="control-group"><label class="paramslabel"><span>' . JText::_('COM_QATDATABASE_FIELD_CURRENCY_SYMBOL') . ': </span><input type="text" name="jform[parameters][]" value="' . $data->parameters . '" /></label></div>';
				return $return;
				break;
			
			case 9:
				if($data->parameters == '[editor]') {
					$textareaChecked = '';
					$textareaClass = '';
					$editorChecked = 'checked="checked"';
					$editorClass = ' active';
				} else {
					if($data->parameters == '[textarea]') {
						$textareaChecked = 'checked="checked"';
						$textareaClass = ' active';
						$editorChecked = '';
						$editorClass = '';
					}
				}
				
				$return = '<div class="control-group"><div class="controls" style="margin: 0px !important;"><span>' . JText::_('COM_QATDATABASE_TYPE') . ': </span><fieldset class="radio btn-group btn-group-yesno"><input id="field-option-textarea-editor-editor-type" ' . $editorChecked . ' value="[editor]" type="radio" name="jform[parameters][]" /><label id="field-option-textarea-editor-editor-type-label" onclick="this.addClass(\'active\'); this.addClass(\'btn-success\'); document.getElementById(\'field-option-textarea-editor-textarea-type-label\').removeClass(\'btn-success\'); document.getElementById(\'field-option-textarea-editor-textarea-type-label\').removeClass(\'active\');" class="btn' . $editorClass . '" for="field-option-textarea-editor-editor-type">' . JText::_('COM_QATDATABASE_FIELD_EDITOR') . '</label><input id="field-option-textarea-editor-textarea-type" ' . $textareaChecked . ' value="[textarea]" type="radio" name="jform[parameters][]" /><label onclick="this.addClass(\'active\'); this.addClass(\'btn-success\'); document.getElementById(\'field-option-textarea-editor-editor-type-label\').removeClass(\'btn-success\'); document.getElementById(\'field-option-textarea-editor-editor-type-label\').removeClass(\'active\');" id="field-option-textarea-editor-textarea-type-label" class="btn' . $textareaClass . '" for="field-option-textarea-editor-textarea-type">' . JText::_('COM_QATDATABASE_FIELD_TEXTAREA') . '</label></fieldset></div></div><div class="control-group"><label class="paramslabel"><span>' . JText::_('COM_QATDATABASE_FIELD_ROWS') . ': </span><input type="number" name="jform[rows]" value="' . $data->rows . '" /></label></div><div class="control-group"><label class="paramslabel"><span>' . JText::_('COM_QATDATABASE_FIELD_COLUMNS') . ': </span><input type="number" name="jform[cols]" value="' . $data->cols . '" /></label></div>';
				return $return;
				break;
			
			case 10:
				$return = '<div><label class="paramslabel"><span>' . JText::_('COM_QATDATABASE_FIELD_MAX_LENGTH') . ': </span><input type="number" name="jform[max_length]" value="' . $data->max_length . '" /></label></div>';
				return $return;
				break;
			
			case 11:
				$return = '<div class="control-group"><label class="paramslabel"><span>' . JText::_('COM_QATDATABASE_FIELD_MAX_LENGTH') . ': </span><input type="number" name="jform[max_length]" value="' . $data->max_length . '" /></label></div><div class="control-group"><label class="paramslabel"><span>' . JText::_('COM_QATDATABASE_FIELD_LINK_TEXT') . ': </span><input type="text" name="jform[parameters][]" value="' . $data->parameters . '" /></label></div>';
				return $return;
				break;
			
			case 12:
				$count = '0';
				$return = '<div id="paramscontr" class="paramscz control-group"><input type="button" onclick="AddMore(\'1\');" class="btn btn-success" value="' . JText::_('COM_QATDATABASE_FIELD_ADD_MORE') . '" /></div>';
				foreach($data->names as $name) {
					if($name !== '') {
						if($count == 0) {
							$return .= '<div class="control-group"><div title="' . JText::_('COM_QATDATABASE_FIELD_MOVE_UP') . '" onclick="Move(this, \'up\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-up"><span></span></div><div title="' . JText::_('COM_QATDATABASE_FIELD_MOVE_DOWN') . '" onclick="Move(this, \'down\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-down"><span></span></div><input title="' . JText::_('COM_QATDATABASE_FIELD_NAME') . '" class="hasTooltip field-params-input" placeholder="' . JText::_('COM_QATDATABASE_FIELD_NAME') . '" type="text" name="jform[names][]" value="' . $name . '" /><input title="' . JText::_('COM_QATDATABASE_FIELD_VALUE') . '" class="hasTooltip field-params-input" placeholder="' . JText::_('COM_QATDATABASE_FIELD_VALUE') . '" type="text" name="jform[values][]" value="' . $data->values[$count] . '" /></div>';
						} else {
							if($count > 0) {
								$return .= '<div id="params" class="paramsc' . $count . ' control-group"><div title="' . JText::_('COM_QATDATABASE_FIELD_MOVE_UP') . '" onclick="Move(this, \'up\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-up"><span></span></div><div title="' . JText::_('COM_QATDATABASE_FIELD_MOVE_DOWN') . '" onclick="Move(this, \'down\')" class="hasTooltip btn btn-info btn-small field-params-input field-button-down"><span></span></div><input title="' . JText::_('COM_QATDATABASE_FIELD_NAME') . '" class="hasTooltip field-params-input" placeholder="' . JText::_('COM_QATDATABASE_FIELD_NAME') . '" type="text" name="jform[names][]" value="' . $name . '" /><input title="' . JText::_('COM_QATDATABASE_FIELD_VALUE') . '" class="hasTooltip field-params-input" placeholder="' . JText::_('COM_QATDATABASE_FIELD_VALUE') . '" type="text" name="jform[values][]" value="' . $data->values[$count] . '" /><input onclick="removeField(this);" type="button" value="' . JText::_('COM_QATDATABASE_FIELD_REMOVE') . '" class="btn btn-small btn-danger" /></div>';
							}
						}
						$count++;
					}
				}
				return $return;
				break;
			
			case 13:
				$return = '<div class="control-group"><label>' . JText::_('COM_QATDATABASE_FIELD_FILE_MAX_FILE_SIZE') . ': <input value="' . ((isset($data->max_file_size) && $data->max_file_size !== '0') ? $data->max_file_size : '') . '" type="number" name="jform[max_file_size]" /></label></div>';
				$return .= '<div class="control-group"><label>' . JText::_('COM_QATDATABASE_FIELD_FILE_ALLOWED_EXTENSION') . ': <input value="' . ((isset($data->parameters)) ? $data->parameters : '') . '" type="text" name="jform[parameters]" /></label></div>';
				return $return;
				break;
				
			case 14:
				break;
		}
	}
	
	public function required($pks, $value = 0) {
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);
		
		$canDo = JHelperContent::getActions('com_qatdatabase');
		
		if(empty($pks)) {
			$this->setError(JText::_('COM_QATDATABASE_NO_ITEM_SELECTED'));
			return false;
		}
		
		try {
			if($canDo->get('core.edit.state')) {
				$db = $this->getDBO();
				
				$query = $db->getQuery(true)->update($db->quoteName('#__qatdatabase_fields'))->set('required=' . (int) $value)->where('id IN (' . implode(',', $pks) . ')');
				
				$db->setQuery($query);
				$db->execute();
			}
		}
		
		catch(Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}
		
		return true;
	}
	
	public function editable($ids, $value = 0) {
		$ids = (array) $ids;
		JArrayHelper::toInteger($ids);
		
		$canDo = JHelperContent::getActions('com_qatdatabase');
		
		if(empty($ids)){
			$this->setError(JText::_('COM_QATDATABASE_NO_FIELD_SELECTED'));
			return false;
		}
		
		try {
			if($canDo->get('core.edit.state')) {
				$db = $this->getDBO();
				$db->setQuery($db->getQuery(true)->update($db->quoteName('#__qatdatabase_fields'))->set('editable=' . (int) $value)->where('id IN (' . implode(',', $ids) . ')'))->execute();
			}
		}
		
		catch(Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}
		
		return true;
	}
	
	public function save($data) {
		if(!isset($data['created']) || (isset($data['created']) && ($data['created'] == '' || $data['created'] == '0000-00-00 00:00:00'))) {
			$data['created'] = JFactory::getDate()->toSql();
		}
		
		if(!isset($data['publish_up']) || (isset($data['publish_up']) && ($data['publish_up'] == '' || $data['publish_up'] == '0000-00-00 00:00:00'))) {
			$data['publish_up'] = JFactory::getDate()->toSql();
		}
		
		if(parent::save($data)) {
			JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_qatdatabase' . DS . 'models', 'QatDatabaseModel');
			$layoutModel = JModelLegacy::getInstance('Layout', 'QatDatabaseModel');
			$layoutModel->CheckTheNewFields();
			return true;
		}
		
		return false;
	}
}
?>