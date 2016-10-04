<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->SideBarMenus; ?>
</div>
<div id="j-main-container" class="span10">
	<h1>Post form layout</h1>
	<div id="post-form-layout" class="ordering-layout">
		<?php
		$Model = $this->getModel();
		$DetailsArray = array('1-status-name' => JText::_('JPUBLISHED'), '0-status-name' => JText::_('JUNPUBLISHED'), '1-status-class' => 'publish', '0-status-class' => 'unpublish', '1-required-name' => JText::_('COM_QATDATABASE_FIELD_REQUIRED'), '0-required-name' => JText::_('COM_QATDATABASE_FIELD_NOT_REQUIRED'), '1-editable-name' => JText::_('COM_QATDATABASE_FIELD_EDITABLE'), '0-editable-name' => JText::_('COM_QATDATABASE_FIELD_NOT_EDITABLE'), '1-required-class' => 'pin red', '0-required-class' => 'pin', '1-editable-class' => 'pencil blue', '0-editable-class' => 'pencil');
		
		foreach($Model->GetFieldsOrdering() as $i => $field) {
			$Details = '';
			
			if($field->description !== '') {
				$Details .= '<span style="color: #3977AD;" title="' . $field->description . '" class="hasTooltip small icon-notification"></span>';
			}
			
			$Details .= '<span title="' . $DetailsArray[$field->editable . '-editable-name'] . '" class="hasTooltip small icon-' . $DetailsArray[$field->editable . '-editable-class'] . '"></span>';
			$Details .= '<span title="' . $DetailsArray[$field->required . '-required-name'] . '" class="hasTooltip small icon-' . $DetailsArray[$field->required . '-required-class'] . '"></span>';
			$Details .= '<span title="' . $DetailsArray[$field->published . '-status-name'] . '" class="hasTooltip small icon-' . $DetailsArray[$field->published . '-status-class'] . '"></span>';
			
			echo '<div class="ordering-item"><div class="ordering-layout-item-icons">' . $Details . '</div><span>' . $field->title . ' <span class="small">(' . $field->name . ')</span></span></div>';
		}
		?>
	</div>
</div>
