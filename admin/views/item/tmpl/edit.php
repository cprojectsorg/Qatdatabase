<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>
<form action="<?php echo JRoute::_('index.php?option=com_qatdatabase&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'contentsTab', array('active' => 'contentTab')); ?>
			<?php echo JHtml::_('bootstrap.addTab', 'contentsTab', 'contentTab', JText::_('COM_QATDATABASE_TAB_ITEM_CONTENTS', true)); ?>
				<div class="row-fluid">
					<div class="span9">
						<fieldset class="adminform">
							<?php
							echo $this->model->LoadFields();
							?>
						</fieldset>
					</div>
					<div class="span3">
						<?php echo $this->model->loadPublishedField($this->item->published); ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php echo JHtml::_('bootstrap.addTab', 'contentsTab', 'publishingTab', JText::_('COM_QATDATABASE_ITEM_PUBLISHING', true)); ?>
				<div class="row-fluid form-horizontal-desktop">
					<div class="span6">
						<div class="control-group">
							<?php
							echo '<div class="control-label">';
							echo '<label for="publish_up">' . JText::_('JGLOBAL_FIELD_PUBLISH_UP_LABEL') . ':</label>';
							echo '</div>';
							echo '<div class="controls">';
							echo JHTML::calendar('', 'publish_up', 'publish_up', '', '');
							echo '</div>';
							?>
						</div>
						<div class="control-group">
							<?php
							echo '<div class="control-label">';
							echo '<label for="publish_down">' . JText::_('JGLOBAL_FIELD_PUBLISH_DOWN_LABEL') . ':</label>';
							echo '</div>';
							echo '<div class="controls">';
							echo JHTML::calendar('', 'publish_down', 'publish_down', '', '');
							echo '</div>';
							?>
						</div>
						<div class="control-group">
							<?php
							echo '<div class="control-label">';
							echo '<label for="created">' . JText::_('JGLOBAL_CREATED_DATE') . ':</label>';
							echo '</div>';
							echo '<div class="controls">';
							echo JHTML::calendar('', 'created', 'created', '', '');
							echo '</div>';
							?>
						</div>
						<script type="text/javascript">
							function SetPubdata() {
								setTimeout(function() {
									document.getElementById('publish_up').setAttribute('value', '<?php echo str_replace('0000-00-00 00:00:00', '', $this->item->publish_up); ?>');
									document.getElementById('publish_down').setAttribute('value', '<?php echo str_replace('0000-00-00 00:00:00', '', $this->item->publish_down); ?>');
									document.getElementById('created').setAttribute('value', '<?php echo str_replace('0000-00-00 00:00:00', '', $this->item->created); ?>');
								}, 1);
							}
							
							SetPubdata();
						</script>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	<input type="hidden" name="id" value="<?php echo (int) $this->item->id; ?>" />
	<input type="hidden" name="task" value="item.edit" />
	<?php echo JHtml::_('form.token'); ?>
</form>