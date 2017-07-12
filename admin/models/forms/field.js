jQuery(function() {
	document.formvalidator.setHandler('title',
		function (value) {
			regex=/[^A-Za-z0-9\-]/;
			return regex.test(value);
		});
});

morefieldsparam = 0;

function removeField(field) {
	jQuery(field).parents('div#params').remove();
}

function AddMore(field) {
	morefieldsparam++;
	var afparams1 = '<div id="params" class="paramsc' + morefieldsparam + ' control-group"><div title="' + Joomla.JText._('COM_QATDATABASE_FIELD_MOVE_UP', 'Move Up') + '" onclick="Move(this, \'up\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-up"><span></span></div><div title="' + Joomla.JText._('COM_QATDATABASE_FIELD_MOVE_DOWN', 'Move Down') + '" onclick="Move(this, \'down\')" class="hasTooltip btn btn-info btn-small field-params-input field-button-down"><span></span></div><input title="' + Joomla.JText._('COM_QATDATABASE_FIELD_NAME', 'Name') + '" class="hasTooltip field-params-input" placeholder="' + Joomla.JText._('COM_QATDATABASE_FIELD_NAME', 'Name') + '" type="text" name="jform[names][]" /><input title="' + Joomla.JText._('COM_QATDATABASE_FIELD_VALUE', 'Value') + '" class="hasTooltip field-params-input" placeholder="' + Joomla.JText._('COM_QATDATABASE_FIELD_VALUE', 'Value') + '" type="text" name="jform[values][]" /><input onclick="removeField(this);" type="button" value="' + Joomla.JText._('COM_QATDATABASE_FIELD_REMOVE', 'Remove') + '" class="btn btn-small btn-danger" /></div>';
	var afparams1 = '<div id="params" class="paramsc' + morefieldsparam + ' control-group"><div title="' + Joomla.JText._('COM_QATDATABASE_FIELD_MOVE_UP', 'Move Up') + '" onclick="Move(this, \'up\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-up"><span></span></div><div title="' + Joomla.JText._('COM_QATDATABASE_FIELD_MOVE_DOWN', 'Move Down') + '" onclick="Move(this, \'down\')" class="hasTooltip btn btn-info btn-small field-params-input field-button-down"><span></span></div><input title="' + Joomla.JText._('COM_QATDATABASE_FIELD_NAME', 'Name') + '" class="hasTooltip field-params-input" placeholder="' + Joomla.JText._('COM_QATDATABASE_FIELD_NAME', 'Name') + '" type="text" name="jform[names][]" /><input title="' + Joomla.JText._('COM_QATDATABASE_FIELD_VALUE', 'Value') + '" class="hasTooltip field-params-input" placeholder="' + Joomla.JText._('COM_QATDATABASE_FIELD_VALUE', 'Value') + '" type="text" name="jform[values][]" /><input onclick="removeField(this);" type="button" value="' + Joomla.JText._('COM_QATDATABASE_FIELD_REMOVE', 'Remove') + '" class="btn btn-small btn-danger" /></div>';
	var afparams4 = '<div id="params" class="paramsc' + morefieldsparam + ' control-group"><div title="' + Joomla.JText._('COM_QATDATABASE_FIELD_MOVE_UP', 'Move Up') + '" onclick="Move(this, \'up\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-up"><span></span></div><div title="' + Joomla.JText._('COM_QATDATABASE_FIELD_MOVE_DOWN', 'Move Down') + '" onclick="Move(this, \'down\')" class="hasTooltip btn btn-info btn-small field-params-input field-button-down"><span></span></div><input title="' + Joomla.JText._('COM_QATDATABASE_FIELD_NAME', 'Name') + '" class="hasTooltip field-params-input" placeholder="' + Joomla.JText._('COM_QATDATABASE_FIELD_NAME', 'Name') + '" type="text" name="jform[names][]" /><input title="' + Joomla.JText._('COM_QATDATABASE_FIELD_VALUE', 'Value') + '" class="hasTooltip field-params-input" placeholder="' + Joomla.JText._('COM_QATDATABASE_FIELD_VALUE', 'Value') + '" type="text" name="jform[values][]" /><input onclick="removeField(this);" type="button" value="' + Joomla.JText._('COM_QATDATABASE_FIELD_REMOVE', 'Remove') + '" class="btn btn-small btn-danger" /></div>';
	var afparams5 = '<div id="params" class="paramsc' + morefieldsparam + ' control-group"><div title="' + Joomla.JText._('COM_QATDATABASE_FIELD_MOVE_UP', 'Move Up') + '" onclick="Move(this, \'up\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-up"><span></span></div><div title="' + Joomla.JText._('COM_QATDATABASE_FIELD_MOVE_DOWN', 'Move Down') + '" onclick="Move(this, \'down\')" class="hasTooltip btn btn-info btn-small field-params-input field-button-down"><span></span></div><input title="' + Joomla.JText._('COM_QATDATABASE_FIELD_NAME', 'Name') + '" class="hasTooltip field-params-input" placeholder="' + Joomla.JText._('COM_QATDATABASE_FIELD_NAME', 'Name') + '" type="text" name="jform[names][]" /><input title="' + Joomla.JText._('COM_QATDATABASE_FIELD_VALUE', 'Value') + '" class="hasTooltip field-params-input" placeholder="' + Joomla.JText._('COM_QATDATABASE_FIELD_VALUE', 'Value') + '" type="text" name="jform[values][]" /><input onclick="removeField(this);" type="button" value="' + Joomla.JText._('COM_QATDATABASE_FIELD_REMOVE', 'Remove') + '" class="btn btn-small btn-danger" /></div>';
	jQuery('#field-params-ele').append(eval('afparams' + field));
	jQuery('.hasTooltip').tooltip({"html": true,"container": "body"});
}

function GetParams(field) {
	var fparams1 = '<div id="paramscontr" class="paramscz control-group"><input type="button" onclick="AddMore(\'1\');" class="btn btn-success" value="' + Joomla.JText._('COM_QATDATABASE_FIELD_ADD_MORE', 'Add more') + '" /></div><div class="control-group"><div title="' + Joomla.JText._('COM_QATDATABASE_FIELD_MOVE_UP', 'Move Up') + '" onclick="Move(this, \'up\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-up"><span></span></div><div title="' + Joomla.JText._('COM_QATDATABASE_FIELD_MOVE_DOWN', 'Move Down') + '" onclick="Move(this, \'down\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-down"><span></span></div><input title="' + Joomla.JText._('COM_QATDATABASE_FIELD_NAME', 'Name') + '" class="hasTooltip field-params-input" placeholder="' + Joomla.JText._('COM_QATDATABASE_FIELD_NAME', 'Name') + '" type="text" name="jform[names][]" /><input title="' + Joomla.JText._('COM_QATDATABASE_FIELD_VALUE', 'Value') + '" class="hasTooltip field-params-input" placeholder="' + Joomla.JText._('COM_QATDATABASE_FIELD_VALUE', 'Value') + '" type="text" name="jform[values][]" /></div>';
	var fparams2 = '';
	var fparams3 = '<div id="paramscontr" class="paramscz control-group"><div class="controls" style="margin: 0px !important;"><span>' + Joomla.JText._('COM_QATDATABASE_FIELD_CALENDAR_STYLE', 'Calender style') + ': </span><fieldset class="radio btn-group btn-group-yesno"><input id="field-option-type-jtype" checked="checked" value="[jtype]" type="radio" name="jform[parameters][]"><label id="field-option-caltype-jtype-label" onclick="this.addClass(\'active\'); this.addClass(\'btn-success\'); document.getElementById(\'field-option-caltype-btype-label\').removeClass(\'btn-success\'); document.getElementById(\'field-option-caltype-btype-label\').removeClass(\'active\');" class="btn active btn-success" for="field-option-type-jtype" aria-invalid="false">' + Joomla.JText._('COM_QATDATABASE_FIELD_CALENDAR_STYLE_JOOMLA', 'Joomla style') + '</label><input id="field-option-type-btype" value="[btype]" type="radio" name="jform[parameters][]"><label onclick="this.addClass(\'active\'); this.addClass(\'btn-success\'); document.getElementById(\'field-option-caltype-jtype-label\').removeClass(\'btn-success\'); document.getElementById(\'field-option-caltype-jtype-label\').removeClass(\'active\');" id="field-option-caltype-btype-label" class="btn" for="field-option-type-btype" aria-invalid="false">' + Joomla.JText._('COM_QATDATABASE_FIELD_CALENDAR_STYLE_DEFAULT', 'Default style') + '</label></fieldset></div></div>';
	var fparams4 = '<div id="paramscontr" class="paramscz control-group"><input type="button" onclick="AddMore(\'4\');" class="btn btn-success" value="' + Joomla.JText._('COM_QATDATABASE_FIELD_ADD_MORE', 'Add more') + '" /></div><div class="control-group"><div title="' + Joomla.JText._('COM_QATDATABASE_FIELD_MOVE_UP', 'Move Up') + '" onclick="Move(this, \'up\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-up"><span></span></div><div title="' + Joomla.JText._('COM_QATDATABASE_FIELD_MOVE_DOWN', 'Move Down') + '" onclick="Move(this, \'down\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-down"><span></span></div><input title="' + Joomla.JText._('COM_QATDATABASE_FIELD_NAME', 'Name') + '" class="hasTooltip field-params-input" placeholder="' + Joomla.JText._('COM_QATDATABASE_FIELD_NAME', 'Name') + '" type="text" name="jform[names][]" /><input title="' + Joomla.JText._('COM_QATDATABASE_FIELD_VALUE', 'Value') + '" class="hasTooltip field-params-input" placeholder="' + Joomla.JText._('COM_QATDATABASE_FIELD_VALUE', 'Value') + '" type="text" name="jform[values][]" /></div>';
	var fparams5 = '<div id="paramscontr" class="paramscz control-group"><input type="button" onclick="AddMore(\'5\');" class="btn btn-success" value="' + Joomla.JText._('COM_QATDATABASE_FIELD_ADD_MORE', 'Add more') + '" /></div><div class="control-group"><div title="' + Joomla.JText._('COM_QATDATABASE_FIELD_MOVE_UP', 'Move Up') + '" onclick="Move(this, \'up\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-up"><span></span></div><div title="' + Joomla.JText._('COM_QATDATABASE_FIELD_MOVE_DOWN', 'Move Down') + '" onclick="Move(this, \'down\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-down"><span></span></div><input title="' + Joomla.JText._('COM_QATDATABASE_FIELD_NAME', 'Name') + '" class="hasTooltip field-params-input" placeholder="' + Joomla.JText._('COM_QATDATABASE_FIELD_NAME', 'Name') + '" type="text" name="jform[names][]" /><input title="' + Joomla.JText._('COM_QATDATABASE_FIELD_VALUE', 'Value') + '" class="hasTooltip field-params-input" placeholder="' + Joomla.JText._('COM_QATDATABASE_FIELD_VALUE', 'Value') + '" type="text" name="jform[values][]" /></div>';
	var fparams6 = '<div><label class="paramslabel"><span>' + Joomla.JText._('COM_QATDATABASE_FIELD_MAX_LENGTH', 'Max Length') + ': </span><input type="number" name="jform[max_length]" value="255" /></label></div>';
	var fparams7 = '<div><label class="paramslabel"><span>' + Joomla.JText._('COM_QATDATABASE_FIELD_MAX_LENGTH', 'Max Length') + ': </span><input type="number" name="jform[max_length]" value="255" /></label></div>';
	var fparams8 = '<div class="control-group"><label class="paramslabel"><span>' + Joomla.JText._('COM_QATDATABASE_FIELD_MAX_LENGTH', 'Max Length') + ': </span><input type="number" name="jform[max_length]" value="255" /></label></div><div class="control-group"><label class="paramslabel"><span>' + Joomla.JText._('COM_QATDATABASE_FIELD_CURRENCY_SYMBOL', 'Currency symbol') + ': </span><input type="text" name="jform[parameters][]" /></label></div>';
	var fparams9 = '<div class="control-group"><div class="controls" style="margin: 0px !important;"><span>' + Joomla.JText._('COM_QATDATABASE_TYPE', 'Type') + ': </span><fieldset class="radio btn-group btn-group-yesno"><input id="field-option-textarea-editor-editor-type" value="[editor]" type="radio" name="jform[parameters][]" /><label id="field-option-textarea-editor-editor-type-label" onclick="this.addClass(\'active\'); this.addClass(\'btn-success\'); document.getElementById(\'field-option-textarea-editor-textarea-type-label\').removeClass(\'btn-success\'); document.getElementById(\'field-option-textarea-editor-textarea-type-label\').removeClass(\'active\');" class="btn" for="field-option-textarea-editor-editor-type">' + Joomla.JText._('COM_QATDATABASE_FIELD_EDITOR', 'Editor') + '</label><input id="field-option-textarea-editor-textarea-type" checked="checked" value="[textarea]" type="radio" name="jform[parameters][]" /><label onclick="this.addClass(\'active\'); this.addClass(\'btn-success\'); document.getElementById(\'field-option-textarea-editor-editor-type-label\').removeClass(\'btn-success\'); document.getElementById(\'field-option-textarea-editor-editor-type-label\').removeClass(\'active\');" id="field-option-textarea-editor-textarea-type-label" class="btn btn-success active" for="field-option-textarea-editor-textarea-type">' + Joomla.JText._('COM_QATDATABASE_FIELD_TEXTAREA', 'Textarea') + '</label></fieldset></div></div><div class="control-group"><label class="paramslabel"><span>' + Joomla.JText._('COM_QATDATABASE_FIELD_ROWS', 'Rows') + ': </span><input type="number" name="jform[rows]" /></label></div><div class="control-group"><label class="paramslabel"><span>' + Joomla.JText._('COM_QATDATABASE_FIELD_COLUMNS', 'Columns') + ': </span><input type="number" name="jform[cols]" /></label></div>';
	var fparams10 = '<div><label class="paramslabel"><span>' + Joomla.JText._('COM_QATDATABASE_FIELD_MAX_LENGTH', 'Max Length') + ': </span><input type="number" name="jform[max_length]" value="255" /></label></div>';
	var fparams11 = '<div class="control-group"><label class="paramslabel"><span>' + Joomla.JText._('COM_QATDATABASE_FIELD_MAX_LENGTH', 'Max Length') + ': </span><input type="number" name="jform[max_length]" value="255" /></label></div><div class="control-group"><label class="paramslabel"><span>' + Joomla.JText._('COM_QATDATABASE_FIELD_LINK_TEXT', 'Link text') + ': </span><input type="text" name="jform[parameters][]" /></label></div>';
	var fparams12 = '<div id="paramscontr" class="paramscz control-group"><input type="button" onclick="AddMore(\'1\');" class="btn btn-success" value="' + Joomla.JText._('COM_QATDATABASE_FIELD_ADD_MORE', 'Add more') + '" /></div><div class="control-group"><div title="' + Joomla.JText._('COM_QATDATABASE_FIELD_MOVE_UP', 'Move Up') + '" onclick="Move(this, \'up\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-up"><span></span></div><div title="' + Joomla.JText._('COM_QATDATABASE_FIELD_MOVE_DOWN', 'Move Down') + '" onclick="Move(this, \'down\');" class="hasTooltip btn btn-info btn-small field-params-input field-button-down"><span></span></div><input title="' + Joomla.JText._('COM_QATDATABASE_FIELD_NAME', 'Name') + '" class="hasTooltip field-params-input" placeholder="' + Joomla.JText._('COM_QATDATABASE_FIELD_NAME', 'Name') + '" type="text" name="jform[names][]" /><input title="' + Joomla.JText._('COM_QATDATABASE_FIELD_VALUE', 'Value') + '" class="hasTooltip field-params-input" placeholder="' + Joomla.JText._('COM_QATDATABASE_FIELD_VALUE', 'Value') + '" type="text" name="jform[values][]" /></div>';
	var fparams13 = '<div class="control-group"><label>' + Joomla.JText._('COM_QATDATABASE_FIELD_FILE_MAX_FILE_SIZE', 'Max file size') + ': <input type="number" name="jform[max_file_size]" /></label></div><div class="control-group"><label>' + Joomla.JText._('COM_QATDATABASE_FIELD_FILE_ALLOWED_EXTENSION', 'Allowed extension') + ': <input type="text" name="jform[parameters]" /></label></div>';
	
	if(field == '') {
		jQuery('#field-params-ele').html('');
		jQuery('.paramsmain').css('display', 'none');
	} else {
		if(eval('fparams' + field) == '') {
			jQuery('.paramsmain').css('display', 'none');
		} else {
			jQuery('.paramsmain').css('display', 'block');
			jQuery('#field-params-ele').html(eval('fparams' + field));
		}
	}
	
	jQuery('.hasTooltip').tooltip({"html": true,"container": "body"});
}

function Move(element, direction) {
	if(direction == 'up') {
		if(jQuery(element).parent().prev().attr('id') !== 'paramscontr') {
			jQuery(element).parent().insertBefore(jQuery(element).parent().prev());
		}
	}
	
	if(direction == 'down') {
		jQuery(element).parent().insertAfter(jQuery(element).parent().next());
	}
}