<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

abstract class JHtmlFields {
	public static function required($value = '0', $i) {
		JHtml::_('bootstrap.tooltip');
		
		if($value == '1') {
			$Toggle = 'fields.notrequired';
		} else {
			$Toggle = 'fields.required';
		}
		
		$return = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $Toggle . '\')" class="btn btn-micro hasTooltip' . ($value == 1 ? ' active red' : '') . '" title="' . JHtml::tooltipText(($value == '1') ? 'COM_QATDATABASE_FIELD_REQUIRED_BUTTON' : 'COM_QATDATABASE_FIELD_NOT_REQUIRED_BUTTON') . '"><span class="icon-pin"></span></a>';
		return $return;
	}
	
	public static function editable($value = '0', $i) {
		JHtml::_('bootstrap.tooltip');
		
		if($value == '1') {
			$Toggle = 'fields.noteditable';
		} else {
			$Toggle = 'fields.editable';
		}
		
		$return = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $Toggle . '\')" class="btn btn-micro hasTooltip' . ($value == 1 ? ' active blue' : '') . '" title="' . JHtml::tooltipText(($value == '1') ? 'COM_QATDATABASE_FIELD_EDITABLE_BUTTON' : 'COM_QATDATABASE_FIELD_NOT_EDITABLE_BUTTON') . '"><span class="icon-pencil"></span></a>';
		return $return;
	}
}
?>
