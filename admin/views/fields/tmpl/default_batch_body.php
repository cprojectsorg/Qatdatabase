<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

$published = $this->state->get('filter.published');
?>
<div class="row-fluid">
	<?php
	if($published >= 0) {
	?>
		<div class="control-group span6">
			<div class="controls">
				<?php echo JHtml::_('batch.item', 'com_qatdatabase'); ?>
			</div>
		</div>
	<?php
	}
	?>
</div>
