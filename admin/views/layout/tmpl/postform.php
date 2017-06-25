<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

?>
<form action="<?php echo JRoute::_('index.php?option=com_qatdatabase&view=layout&layout=postform'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->SideBarMenus; ?>
	</div>
	<div id="j-main-container" class="span10">
		<h1><?php echo JText::_('COM_QATDATABASE_POST_FORM_LAYOUT'); ?></h1>
		<div id="post-form-layout" class="ordering-layout">
			<?php
			$Model = $this->getModel();
			$DetailsArray = array('2-status-name' => JText::_('JARCHIVED'), '1-status-name' => JText::_('JPUBLISHED'), '0-status-name' => JText::_('JUNPUBLISHED'), '2-status-class' => 'archive', '1-status-class' => 'publish', '0-status-class' => 'unpublish', '1-required-name' => JText::_('COM_QATDATABASE_FIELD_REQUIRED'), '0-required-name' => JText::_('COM_QATDATABASE_FIELD_NOT_REQUIRED'), '1-editable-name' => JText::_('COM_QATDATABASE_FIELD_EDITABLE'), '0-editable-name' => JText::_('COM_QATDATABASE_FIELD_NOT_EDITABLE'), '1-required-class' => 'pin red', '0-required-class' => 'pin', '1-editable-class' => 'pencil blue', '0-editable-class' => 'pencil');
			
			foreach($Model->GetFieldsOrdering() as $i => $field) {
				$Details = '';
				
				if($field->description !== '') {
					$Details .= '<span style="color: #3977AD;" title="' . $field->description . '" class="hasTooltip small icon-notification"></span>';
				} else {
					$Details .= '<span style="color: #333333;" title="<i>' . JText::_('COM_QATDATABASE_FIELD_NO_DESCRIPTION') . '</i>" class="hasTooltip small icon-notification"></span>';
				}
				
				$Details .= '<span title="' . $DetailsArray[$field->editable . '-editable-name'] . '" class="hasTooltip small icon-' . $DetailsArray[$field->editable . '-editable-class'] . '"></span>';
				$Details .= '<span title="' . $DetailsArray[$field->required . '-required-name'] . '" class="hasTooltip small icon-' . $DetailsArray[$field->required . '-required-class'] . '"></span>';
				$Details .= '<span title="' . $DetailsArray[$field->published . '-status-name'] . '" class="hasTooltip small icon-' . $DetailsArray[$field->published . '-status-class'] . '"></span>';
				
				echo '<div onclick="StartOrdering(' . $field->id . ')" id="oim' . $field->id . '" class="ordering-item"><span class="ordering-layout-button"></span><div class="ordering-layout-item-icons">' . $Details . '</div><span><span title="#' . $field->id . '" class="hasTooltip">' . $field->title . '</span> <span class="small hasTooltip" title="' . JText::_('COM_QATDATABASE_FIELD_NAME') . '">(' . $field->name . ')</span></span><input type="hidden" name="jform[qatdatabasefldid][]" value="' . $field->id . '" /></div>';
			}
			?>
		</div>
	</div>
	<input type="hidden" name="task" value="layout.save" />
	<input type="hidden" name="jform[type]" value="postform" />
	<?php echo JHtml::_('form.token'); ?>
</form>
