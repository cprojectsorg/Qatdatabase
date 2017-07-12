<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

JHtml::_('formbehavior.chosen', 'select');

$listOrder = $this->escape($this->filter_order);
$listDirn = $this->escape($this->filter_order_Dir);
?>
<form action="<?php echo JRoute::_('index.php?option=com_qatdatabase&view=items'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->SideBarMenus; ?>
	</div>
	<div id="j-main-container" class="span10">
		<div class="js-stools clearfix">
			<?php
			echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
			?>
		</div>
		<?php
		if(!empty($this->items)):
		?>
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th width="1%"><?php echo JText::_('COM_QATDATABASE_NUM'); ?></th>
					<th width="2%"><?php echo JHtml::_('grid.checkall'); ?></th>
					<?php
					if($this->canDo->get('core.edit.state')) {
					?>
						<th width="5%"><?php echo JHtml::_('grid.sort', 'COM_QATDATABASE_ITEM_STATUS', 'published', $listDirn, $listOrder); ?></th>
					<?php
					}
					?>
					<th><?php echo JText::_('COM_QATDATABASE_ITEM_EDIT_TOOLTIP'); ?></th>
					<th><?php echo JHtml::_('grid.sort', 'COM_QATDATABASE_ITEM_CATEGORY', 'catid', $listDirn, $listOrder); ?></th>
					<th><?php echo JHtml::_('grid.sort', 'COM_QATDATABASE_ID', 'id', $listDirn, $listOrder); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
			</tfoot>
			<tbody>
				<?php if(!empty($this->items)):
				foreach($this->items as $i => $row):
					$link = JRoute::_('index.php?option=com_qatdatabase&task=item.edit&id=' . $row->id);
				?>
					<tr>
						<td><?php echo $this->pagination->getRowOffset($i); ?></td>
						<td><?php echo JHtml::_('grid.id', $i, $row->id); ?></td>
						<?php
						if($this->canDo->get('core.edit.state')) {
						?>
						<td align="center">
							<div class="btn-group">
									<?php
									echo JHtml::_('jgrid.published', $row->published, $i, 'items.', true, 'cb', $row->publish_up, $row->publish_down);
									$trashed = $this->state->get('filter.trashed') == 1 ? true : false;
									$archived = $this->state->get('filter.archived') == 1 ? true : false;
									$menAct = $trashed ? 'unarchive' : 'archive';
									JHtml::_('actionsdropdown.' . $menAct, 'cb' . $i, 'items');
									$menAct = $trashed ? 'untrash' : 'trash';
									JHtml::_('actionsdropdown.' . $menAct, 'cb' . $i, 'items');
									echo JHtml::_('actionsdropdown.render', $this->escape($row->id));
									?>
							</div>
						</td>
						<?php } ?>
						<td>
							<a href="<?php echo $link; ?>" class="hasTooltip" title="<?php echo JText::_('COM_QATDATABASE_ITEM_EDIT_TOOLTIP'); ?>"><?php echo JText::_('COM_QATDATABASE_ITEM_EDIT_TOOLTIP'); ?></a>
						</td>
						<td>
							<?php
							$explodedcats = explode(',', $row->catid);
							if($row->catid == '-1' || strpos($row->catid, '-1') !== false || ($this->model->GetCategoriesNumber() == count($explodedcats) && $this->model->GetCategoriesNumber() > '1')) {
								echo JText::_('COM_QATDATABASE_FIELD_ALL_CATEGORIES');
							} else {
								if(strpos($row->catid, ',') !== false) {
									$count = count($explodedcats) . ' ' . JText::_('COM_QATDATABASE_FIELDS_MORE_THAN_ONE_CATEGORY') . ' <span class="fields-num-categories-small">' . JText::_('COM_QATDATABASE_OF') . ' (' . $this->model->GetCategoriesNumber() . ')</span>';
									?>
									<span title="<?php $this->model->GetCategories($row->catid); ?>" class="hasTooltip fields-more-one-cat"><?php echo $count; ?></span>
									<?php
								} else {
									// Check if all categories option is exist in categories list.
									if(strpos($row->catid, '-1') !== false) {
										echo JText::_('COM_QATDATABASE_FIELD_ALL_CATEGORIES');
									} else {
										echo $row->category_title;
									}
								}
							}
							?>
						</td>
						<td align="center"><?php echo $row->id; ?></td>
					</tr>
				<?php
					endforeach;
				endif;
				?>
			</tbody>
		</table>
		<?php
		else : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php
		endif;
		?>
	</div>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
