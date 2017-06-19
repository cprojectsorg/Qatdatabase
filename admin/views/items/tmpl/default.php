<?php
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
					<th width="5%"><?php echo JHtml::_('grid.sort', 'COM_QATDATABASE_ITEM_STATUS', 'published', $listDirn, $listOrder); ?></th>
					<th><?php echo JHtml::_('grid.sort', 'COM_QATDATABASE_ITEM_TITLE', 'title', $listDirn, $listOrder); ?></th>
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
					$link = JRoute::_('index.php?option=com_qatdatabase&view=item&layout=edit&id=' . $row->id);
				?>
					<tr>
						<td><?php echo $this->pagination->getRowOffset($i); ?></td>
						<td><?php echo JHtml::_('grid.id', $i, $row->id); ?></td>
						<td align="center">
							<div class="btn-group">
								<?php echo JHtml::_('jgrid.published', $row->published, $i, 'items.', true, 'cb', $row->publish_up, $row->publish_down); ?>
								<?php
								$trashed = $this->state->get('filter.trashed') == 1 ? true : false;
								$archived = $this->state->get('filter.archived') == 1 ? true : false;
								$menAct = $trashed ? 'unarchive' : 'archive';
								JHtml::_('actionsdropdown.' . $menAct, 'cb' . $i, 'items');
								$menAct = $trashed ? 'untrash' : 'trash';
								JHtml::_('actionsdropdown.' . $menAct, 'cb' . $i, 'items');
								echo JHtml::_('actionsdropdown.render', $this->escape($row->title));
								?>
							</div>
						</td>
						<td>
							<a href="<?php echo $link; ?>" class="hasTooltip" title="<?php echo JText::_('COM_QATDATABASE_ITEM_EDIT_TOOLTIP'); ?>"><?php echo $row->title; ?></a>
						</td>
						<td>
							<?php echo $row->category_title; ?>
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
