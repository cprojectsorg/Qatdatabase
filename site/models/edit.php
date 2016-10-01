<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.modelitem');
class QatDatabaseModelEdit extends JModelItem {
	public $required;
	public function LoadFields() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__qatdatabase_fields');
		$db->setQuery($query);
		$loadQuery = $db->loadObjectList();
		foreach($loadQuery as $field) {
			if($field->type !== '1' || $field->type !== '4' || $field->type !== '12' || $field->type !== '13') {
				$forLabel = ' for="' . $field->name . '"';
				$forClass = '';
			}
			
			if($field->type == '1' || $field->type == '4' || $field->type == '12' || $field->type == '13') {
				$forLabel = '';
				$forClass = ' text';
			}
			
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
			
			$this->required = array('text' => '*', 'class' => 'required', 'input' => 'required="required"');
			
			echo '<div class="control-group' . $inline . '"><label class="qatdatabase-label' . $forClass . '"' . $forLabel . '>' . $field->title . ': ' . (($field->required == '1') ? '<span class="qatdatabase-required-star">' . $this->required['text'] . '</span> ' : '') . $FieldDesc . '</label><div class="qatdatabase-field' . $inline . '">' . $this->RenderField($field->type, $field->title, $field->name, $field->names, $field->values, $field->rows, $field->cols, $field->parameters, $field->max_length, $field->required) . '</div></div>';
		}
	}
	
	protected function RenderField($TypeId, $FieldTitle, $FieldName, $Names = null, $Values = null, $Rows = null, $Cols = null, $Parameters = null, $MaxLength = null, $Required = '0') {
		if($TypeId == '1') {
			$count = '0';
			$value = explode(',', $Values);
			$names = explode(',', $Names);
			$field = '<div class="qatdatabase-checkbox-group">';
			foreach($names as $name) {
				$field .= '<div id="' . $FieldName . '" class="qatdatabase-checkbox-item"><label class="qatdatabase-label" for="' . $FieldName . $count . '">' . $name . '</label><input ' . (($count == '0') ? 'checked="checked" ' : '') . 'id="' . $FieldName . $count . '" type="checkbox" value="' . $value[$count] . '" name="' . $FieldName . '[]" /></div>';
				$count++;
			}
			$field .= '</div>';
			
			$return = $field;
		}
		
		if($TypeId == '2') {
			$field = '<input class="' . (($Required == '1') ? $this->required['class'] : '') . '" id="' . $FieldName . '" name="' . $FieldName . '" value="' . $FieldTitle . '" type="checkbox"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' />';
			$return = $field;
		}
		
		if($TypeId == '3') {
			$field = '<input class="' . (($Required == '1') ? $this->required['class'] : '') . '" id="' . $FieldName . '" name="' . $FieldName . '" type="date"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' />';
			$return = $field;
		}
		
		if($TypeId == '4') {
			$count = '0';
			$value = explode(',', $Values);
			$names = explode(',', $Names);
			$field = '<div class="qatdatabase-select-group">';
			$field .= '<select id="' . $FieldName .  '" multiple="multiple"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' name="' . $FieldName . '[]" class="qatdatabase-options-field' . (($Required == '1') ? ' ' . $this->required['class'] : '') . '">';
			foreach($names as $name) {
				$field .= '<option ' . (($count == '0') ? 'selected="selected" ' : '') . 'id="' . $FieldName . $count . '" value="' . $value[$count] . '">' . $name . '</option>';
				$count++;
			}
			$field .= '</select>';
			$field .= '</div>';
			
			$return = $field;
		}
		
		if($TypeId == '5') {
			$count = '0';
			$value = explode(',', $Values);
			$names = explode(',', $Names);
			$field = '<div class="qatdatabase-select-group">';
			$field .= '<select id="' . $FieldName . '" name="' . $FieldName . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' class="qatdatabase-option-field' . (($Required == '1') ? ' ' . $this->required['class'] : '') . '">';
			foreach($names as $name) {
				$field .= '<option id="' . $FieldName . $count . '" value="' . $value[$count] . '">' . $name . '</option>';
				$count++;
			}
			$field .= '</select>';
			$field .= '</div>';
			
			$return = $field;
		}
		
		if($TypeId == '6') {
			$field = '<input class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" type="email" maxlength="' . $MaxLength . '" name="' . $FieldName . '" />';
			$return = $field;
		}
		
		if($TypeId == '7') {
			$field = '<input class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" type="number" maxlength="' . $MaxLength . '" name="' . $FieldName . '" />';
			$return = $field;
		}
		
		if($TypeId == '8') {
			$field = '<input class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" type="number" name="' . $FieldName . '" /> <span id="' . $FieldName . 'currency" class="' . $FieldName . ' currency">' . $Parameters . '</span>';
			$return = $field;
		}
		
		if($TypeId == '9') {
			if($Parameters == '[editor]') {
				$editor = &JFactory::getEditor();
				$params = array('smilies' => '0', 'style' => '1', 'layer' => '0', 'table' => '0', 'clear_entities' => '0');
				$field = $editor->display('', '', $Rows * 100, $Cols * 100, $Cols, $Rows, false, $params);
			}
			
			if($Parameters == '[textarea]') {
				$field = '<textarea class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" rows="' . $Rows . '" cols="' . $Cols . '" name="' . $FieldName . '"></textarea>';
			}
			
			$return = $field;
		}
		
		if($TypeId == '10') {
			$field = '<input class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" type="text" name="' . $FieldName . '" maxlength="' . $MaxLength . '" />';
			$return = $field;
		}
		
		if($TypeId == '11') {
			$field = '<input class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' id="' . $FieldName . '" type="url" name="' . $FieldName . '" maxlength="' . $MaxLength . '" />';
			$return = $field;
		}
		
		if($TypeId == '12') {
			$count = '0';
			$value = explode(',', $Values);
			$names = explode(',', $Names);
			$field = '<div class="qatdatabase-radio-group">';
			foreach($names as $name) {
				$field .= '<label class="radio-item-label">' . $name . ' <input ' . (($count == '0') ? 'checked="checked" ' : '') . 'type="radio" id="' . $FieldName . $count . '" name="' . $FieldName . '" value="' . $value[$count] . '" /></label>';
				$count++;
			}
			$field .= '</div>';
			
			$return = $field;
		}
		
		if($TypeId == '13') {
			$field = '<input class="' . (($Required == '1') ? $this->required['class'] : '') . '"' . (($Required == '1') ? ' ' . $this->required['input'] : '') . ' type="file" name="' . $FieldName . '" />';
			return $field;
		}
		
		return $return;
	}
}
?>
