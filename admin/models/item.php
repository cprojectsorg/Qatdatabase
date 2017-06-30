<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.modeladmin');

class QatDatabaseModelItem extends JModelAdmin {
	public function getTable($type = 'Item', $prefix = 'QatdatabaseTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true) {
		
	}
	
	public function getItem($pk = null) {
		if($item = parent::getItem($pk)) {
			// Override itemdata column.
			$item->itemdata = json_decode($item->itemdata);
		}
		
		return $item;
	}
	
	public function postSaveHook($model, $data) {
		$item = $this->getItem();
		return $item->get('id');
	}
	
	public function save($data) {
		// The fields which is not used (Like token) or has a column in `qatdatabase_items` table and is not needed to be inside the itemdata column and is automaticlly saved.
		$exceptedFields = array('task', JSession::getFormToken(), 'id', 'alias', 'created', 'publish_up', 'publish_down', 'published', 'catid');
		
		// Assign each value to a key.
		foreach($exceptedFields as $exceptedField) {
			$exceptedFields[$exceptedField] = '';
		}
		
		
		// Check data.
		if($data == null || $data == '') {
			return false;
		}
		
		if(!isset($data['created']) || (isset($data['created']) && ($data['created'] == '' || $data['created'] == '0000-00-00 00:00:00'))) {
			$data['created'] = JFactory::getDate()->toSql();
		}
		
		if(!isset($data['publish_up']) || (isset($data['publish_up']) && ($data['publish_up'] == '' || $data['publish_up'] == '0000-00-00 00:00:00'))) {
			$data['publish_up'] = JFactory::getDate()->toSql();
		}
		
		// Set the unassigned data variable.
		$unassignedData = array();
		
		// Check the unassigned post data.
		foreach($data as $field => $fieldvalue) {
			if(!isset($exceptedFields[$field])) {
				if(is_array($unassignedData[$field])) {
					$unassignedData[$field] = array($fieldvalue);
				} else {
					$unassignedData[$field] = $fieldvalue;
				}
			}
		}
		
		// Assign all unassigned data to the itemdata column.
		$data['itemdata'] = json_encode($unassignedData);
		
		if(parent::save($data)) {
			return true;
		}
		
		return false;
	}
	
	protected function RenderField($TypeId, $FieldTitle, $FieldName, $Names = null, $Values = null, $Rows = null, $Cols = null, $Parameters = null, $MaxLength = null, $Required = '0', $value = '') {
		if($this->getItem()->id == '0') {
			$ItemFieldValue = null;
		} else {
			if(isset($this->getItem()->itemdata->$FieldName)) {
				$ItemFieldValue = $this->getItem()->itemdata->$FieldName;
			}
		}
		
		switch($TypeId) {
			case 1:
				$count = '0';
				$value = explode(',', $Values);
				$names = explode(',', $Names);
				
				if(isset($ItemFieldValue)) {
					$isChecked = array();
					
					foreach($ItemFieldValue as $key => $val) {
						$isChecked[$val] = 'true';
					}
				}
				
				$field = '<fieldset' . (($Required == '1') ? ' ' . $this->required['input'] . ' ' : ' ') . 'id="' . $FieldName . '" class="qatdatabase-checkbox-group checkboxes ' . (($Required == '1') ? $this->required['class'] : '') . '">';
				foreach($names as $name) {
					if(isset($isChecked[$value[$count]])) {
						$checked = true;
					} else {
						$checked = false;
					}
					
					$field .= '<div id="' . $FieldName . '" class="qatdatabase-checkbox-item">';
					$field .= '<label class="qatdatabase-label" for="' . $FieldName . $count . '">' . $name . '</label>';
					$field .= '<input style="float: none; margin: 0px;"' . ((isset($checked) && $checked !== null && $checked == true) ? ' checked ' : ' ') . 'id="' . $FieldName . $count . '" type="checkbox" value="' . $value[$count] . '" name="' . $FieldName . '[]" />';
					$field .= '</div>';
					$count++;
				}
				$field .= '</fieldset>';
				
				$return = $field;
				break;
				
			case 2:
				$field = '<input' . ((isset($ItemFieldValue) && $ItemFieldValue !== null && $ItemFieldValue == $FieldTitle) ? ' checked ' : ' ') . 'class="' . (($Required == '1') ? $this->required['class'] : '') . '" id="' . $FieldName . '" name="' . $FieldName . '" value="' . $FieldTitle . '" type="checkbox"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' />';
				$return = $field;
				break;
			
			case 3:
				if($Parameters == '[jtype]') {
					$return = JHTML::_('calendar', ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : ''), $FieldName, $FieldName, '%Y-%m-%d', array('class' => 'required'));
				} elseif($Parameters == '[btype]') {
					$field = '<input value="' . ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : '') . '" class="' . (($Required == '1') ? $this->required['class'] : '') . '" id="' . $FieldName . '" name="' . $FieldName . '" type="date"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' />';
					$return = $field;
				}
				break;
			
			case 4:
				$count = '0';
				$value = explode(',', $Values);
				$names = explode(',', $Names);
				
				if(isset($ItemFieldValue)) {
					$isChecked = array();
					
					// Convert values to keys (default keys not needed)
					foreach($ItemFieldValue as $key => $val) {
						$isChecked[$val] = 'true';
					}
				}
				
				$field = '<div class="qatdatabase-select-group">';
				$field .= '<select id="' . $FieldName .  '" multiple="multiple"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' name="' . $FieldName . '[]" class="qatdatabase-options-field' . (($Required == '1') ? ' ' . $this->required['class'] : '') . '">';
				foreach($names as $name) {
					if(isset($isChecked[$value[$count]])) {
						$checked = true;
					} else {
						$checked = false;
					}
					$field .= '<option' . ((isset($checked) && $checked !== null && $checked == true) ? ' selected ' : ' ') . 'id="' . $FieldName . $count . '" value="' . $value[$count] . '">' . $name . '</option>';
					$count++;
				}
				$field .= '</select>';
				$field .= '</div>';
				
				$return = $field;
				break;
			
			case 5:
				$count = '0';
				$value = explode(',', $Values);
				$names = explode(',', $Names);
				$field = '<div class="qatdatabase-select-group">';
				$field .= '<select id="' . $FieldName . '" name="' . $FieldName . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' class="qatdatabase-option-field' . (($Required == '1') ? ' ' . $this->required['class'] : '') . '">';
				foreach($names as $name) {
					$field .= '<option' . ((isset($ItemFieldValue) && $ItemFieldValue !== null && $ItemFieldValue == $value[$count]) ? ' selected ' : ' ') . ' id="' . $FieldName . $count . '" value="' . $value[$count] . '">' . $name . '</option>';
					$count++;
				}
				$field .= '</select>';
				$field .= '</div>';
				
				$return = $field;
				break;
			
			case 6:
				$field = '<input value="' . ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : '') . '" class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" type="email" maxlength="' . $MaxLength . '" name="' . $FieldName . '" />';
				$return = $field;
				break;
			
			case 7:
				$field = '<input value="' . ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : '') . '" class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" type="number" maxlength="' . $MaxLength . '" name="' . $FieldName . '" />';
				$return = $field;
				break;
			
			case 8:
				$field = '<input value="' . ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : '') . '" class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" type="number" name="' . $FieldName . '" /> <span id="' . $FieldName . 'currency" class="' . $FieldName . ' currency">' . $Parameters . '</span>';
				$return = $field;
				break;
			
			case 9:
				if($Parameters == '[editor]') {
					$editor = JFactory::getEditor();
					$params = array('smilies' => '1', 'style' => '1', 'layer' => '0', 'table' => '0', 'clear_entities' => '0');
					$field = $editor->display($FieldName, ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : ''), $Rows * 10, $Cols * 10, $Cols, $Rows, false, '', '', '', $params);
				}
				
				if($Parameters == '[textarea]') {
					$field = '<textarea class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" rows="' . $Rows . '" cols="' . $Cols . '" name="' . $FieldName . '">' . ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : '') . '</textarea>';
				}
				
				$return = $field;
				break;
			
			case 10:
				$field = '<input value="' . ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : '') . '" class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" type="text" name="' . $FieldName . '" maxlength="' . $MaxLength . '" />';
				$return = $field;
				break;
			
			case 11:
				$field = '<input value="' . ((isset($ItemFieldValue) && $ItemFieldValue !== null) ? $ItemFieldValue : '') . '" class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" type="url" name="' . $FieldName . '" maxlength="' . $MaxLength . '" />';
				$return = $field;
				break;
			
			case 12:
				$count = '0';
				$value = explode(',', $Values);
				$names = explode(',', $Names);
				$field = '<fieldset' . (($Required == '1') ? ' ' . $this->required['input'] . ' ' : ' ') . 'id="' . $FieldName . '" class="qatdatabase-radio-group radio ' . (($Required == '1') ? $this->required['class'] : '') . '">';
				foreach($names as $name) {
					$field .= '<label class="radio-item-label"><span>' . $name . '</span> ';
					$field .= '<input style="float: none; margin: 0px;"' . ((isset($ItemFieldValue) && $ItemFieldValue !== null && $ItemFieldValue == $value[$count]) ? ' checked ' : ' ') . 'type="radio" id="' . $FieldName . $count . '" name="' . $FieldName . '"' . (($Required == '1') ? ' ' . $this->required['input'] . ' ' : ' ') . 'value="' . $value[$count] . '" />';
					$field .= '</label>';
					$count++;
				}
				$field .= '</fieldset>';
				
				$return = $field;
				break;
			
			case 13:
				$field = '<input class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' type="file" name="' . $FieldName . '" />';
				$return = $field;
				break;
		}
		
		return $return;
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
	
	protected function LoadCategoryField($selectedCategory = 0) {
		if(is_numeric($selectedCategory) && $selectedCategory !== 0) {
			$SelectedCatId = $selectedCategory;
		} else {
			$SelectedCatId = '';
		}
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__categories')->where('extension=\'com_qatdatabase\'');
		$db->setQuery($query);
		$Categories = $db->loadObjectList();
		
		$return = '<div class="control-group">';
		$return .= '<div class="control-label">';
		$return .= '<label class="qatdatabase-label" for="qdbcategory"><h4 class="qatdatabase-ilheading">' . JText::_('COM_QATDATABASE_FLD_SELECT_CATEGORY_LABEL') . ': </h4><span class="qatdatabase-required-star">*</span></label>';
		$return .= '</div>';
		$return .= '<select id="qdbcategory" class="required" name="catid">';
		$return .= '<option value="">' . JText::_('COM_QATDATABASE_FLD_SELECT_CATEGORY') . '</option>';
		
		foreach($Categories as $Category) {
			if($SelectedCatId == $Category->id) {
				$isSelected = ' selected';
			} else {
				$isSelected = '';
			}
			
			$return .= '<option' . $isSelected . ' value="' . $Category->id . '">' . $Category->title . '</option>';
		}
		
		$return .= '</select>';
		$return .= '</div>';
		
		return $return;
	}
	
	public function loadPublishedField($Status = 1) {
		$isSelected = array();
		$isSelected[1] = '';
		$isSelected[0] = '';
		$isSelected[2] = '';
		$isSelected[-2] = '';
		$isSelected[$Status] = ' selected';
		
		$return = '<label for="published">' . JText::_('JSTATUS') . ':</label>';
		$return .= '<select id="published" class="chzn-color-state" name="published">';
		$return .= '<option' . $isSelected[1] . ' value="1">' . JText::_('JPUBLISHED') . '</option>';
		$return .= '<option' . $isSelected[0] . ' value="0">' . JText::_('JUNPUBLISHED') . '</option>';
		$return .= '<option' . $isSelected[2] . ' value="2">' . JText::_('JARCHIVED') . '</option>';
		$return .= '<option' . $isSelected[-2] . ' value="-2">' . JText::_('JTRASHED') . '</option>';
		$return .= '</select>';
		
		return $return;
	}
	
	public function LoadFields() {
		if($this->getItem() !== '') {
			$CatId = $this->getItem()->catid;
		} else {
			$CatId = '';
		}
		
		$return = $this->LoadCategoryField($CatId);
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__qatdatabase_fields AS field');
		
		if($this->IsTableEmpty('#__qatdatabase_fields_ordering') == 'false') {
			$query->join('RIGHT', '#__qatdatabase_fields_ordering AS fieldorder ON field.id = fieldorder.fieldid')->order('fieldorder.ordering ASC');
		}
		
		$query->where('field.published=1');
		
		$db->setQuery($query);
		$loadQuery = $db->loadObjectList();
		
		foreach($loadQuery as $field) {
			$forLabel = ' for="' . $field->name . '"';
			$forClass = '';
			
			if($field->type == '2') {
				$inline = ' inline';
			} else {
				$inline = '';
			}
			
			if($field->description !== '') {
				$FieldDesc = '<span title="' . $field->description . '" class="hasTooltip qatdatabase-field-desc">!</span>';
			} else {
				$FieldDesc = '';
			}
			
			if(isset($field->labellink) && $field->labellink !== '' && filter_var($field->labellink, FILTER_VALIDATE_URL) == true) {
				$labellink = '<a href="' . $field->labellink . '" target="_blank">';
				$labellinkend = '</a>';
			} else {
				$labellink = '';
				$labellinkend = '';
			}
			
			$this->required = array('text' => '*', 'class' => 'required', 'input' => 'required="required"');
			
			$return .= '<div class="control-group' . $inline . '">';
			$return .= '<div class="control-label">';
			$return .= '<label class="qatdatabase-label' . $forClass . '"' . $forLabel . '><h4 class="qatdatabase-ilheading">' . $labellink . $field->title . $labellinkend . ': </h4>' . (($field->required == '1') ? '<span class="qatdatabase-required-star">' . $this->required['text'] . '</span> ' : '') . $FieldDesc . '</label>';
			$return .= '</div>';
			$return .= '<div class="controls qatdatabase-field' . $inline . '">';
			$return .= $this->RenderField($field->type, $field->title, $field->name, $field->names, $field->values, $field->rows, $field->cols, $field->parameters, $field->max_length, $field->required);
			$return .= '</div>';
			$return .= '</div>';
		}
		
		return $return;
	}
}
?>
