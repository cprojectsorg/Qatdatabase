<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

if($this->item->id !== null) {
	$new = false;
} else {
	$new = true;
}

if($new == false) {
	// Get field type from model.
	$getModel = $this->getModel();
	$FieldType = $getModel->FieldTypeData();
	if($FieldType == null or $FieldType == '' or $getModel->GetFieldsInputs($FieldType) == null or $getModel->GetFieldsInputs($FieldType) == '') {
		$ParamStyle = 'display: none;';
	} else {
		$ParamStyle = '';
	}
} else {
	$ParamStyle = 'display: none;';
}
?>
<form action="<?php echo JRoute::_('index.php?option=com_qatdatabase&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="control-group" style="margin: 5px 0px;">
		<div style="display: inline-block; vertical-align: top; padding-top: 5px;" class="control-label"><?php echo $this->form->getLabel('title'); ?>&nbsp;&nbsp;</div>
		<div style="display: inline-block;" class="control"><?php echo $this->form->getInput('title'); ?></div>
	</div>
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'MainTab', array('active' => 'mainTab')); ?>
			<?php echo JHtml::_('bootstrap.addTab', 'MainTab', 'mainTab', JText::_('COM_QATDATABASE_FIELD_MAIN', true)); ?>
				<div class="row-fluid">
					<div class="span9">
						<fieldset class="adminform">
							<?php foreach($this->form->getFieldset() as $field):
								$FieldNotDenied = $field->name !== $this->FormPref . '[rows]' && $field->name !== $this->FormPref . '[cols]' && $field->name !== $this->FormPref . '[max_length]' && $field->name !== $this->FormPref . '[id]' && $field->name !== $this->FormPref . '[catid][]' && $field->name !== $this->FormPref . '[parameters]' && $field->name !== $this->FormPref . '[names]' && $field->name !== $this->FormPref . '[values]' && $field->name !== $this->FormPref . '[title]' && $field->name !== $this->FormPref . '[created]' && $field->name !== $this->FormPref . '[published]' && $field->name !== $this->FormPref . '[publish_up]' && $field->name !== $this->FormPref . '[publish_down]' ;
								?>
								<?php if($FieldNotDenied) { ?>
									<div class="control-group">
										<div class="control-label"><?php echo $field->label; ?></div>
										<div class="controls"><?php echo $field->input; ?></div>
									</div>
								<?php
								}
							endforeach;
							?>
							<div class="paramsmain" style="<?php echo $ParamStyle; ?>">
								<div class="field-parameter" style="margin-bottom: 18px;">
									<h3><?php echo JText::_('COM_QATDATABASE_FIELD_PARAMETERS'); ?></h3>
								</div>
								<div id="field-params-ele">
									<?php
									if($new == false && $getModel->GetFieldsInputs($FieldType) !== '' && $getModel->GetFieldsInputs($FieldType) !== null) {
										echo $getModel->GetFieldsInputs($FieldType);
									}
									?>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="span3">
						<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php echo JHtml::_('bootstrap.addTab', 'MainTab', 'publishingTab', JText::_('COM_QATDATABASE_FIELD_PUBLISHING', true)); ?>
				<div class="row-fluid form-horizontal-desktop">
					<div class="span6">
						<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	<?php echo $this->form->getInput('id'); ?>
	<input type="hidden" name="task" value="field.edit" />
	<?php echo JHtml::_('form.token'); ?>
</form>
