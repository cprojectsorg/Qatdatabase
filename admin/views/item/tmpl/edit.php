<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>
<form action="<?php echo JRoute::_('index.php?option=com_qatdatabase&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'contentsTab', array('active' => 'contentTab')); ?>
			<?php echo JHtml::_('bootstrap.addTab', 'contentsTab', 'contentTab', JText::_('COM_QATDATABASE_ITEM_CONTENT', true)); ?>
				<div class="row-fluid">
					<div class="span9">
						<fieldset class="adminform">
							<div class="control-group">
								<div class="control-label" style="display: inline-block !important; float: none !important;">
									<?php echo $this->form->getLabel('content'); ?>
								</div>
								<div class="controls" style="margin: 0px !important;">
									<?php echo $this->form->getInput('content'); ?>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="span3">
						<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php echo JHtml::_('bootstrap.addTab', 'contentsTab', 'publishingTab', JText::_('COM_QATDATABASE_ITEM_PUBLISHING', true)); ?>
				<div class="row-fluid form-horizontal-desktop">
					<div class="span6">
						<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="item.edit" />
	<?php echo JHtml::_('form.token'); ?>
</form>
