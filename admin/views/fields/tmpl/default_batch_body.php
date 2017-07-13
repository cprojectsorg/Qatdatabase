<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

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