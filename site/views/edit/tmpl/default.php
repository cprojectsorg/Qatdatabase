<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
?>
<form class="form-validate" action="<?php echo JRoute::_('index.php?option=com_qatdatabase&view=edit&id=' . (int) $this->item->id); ?>" method="post">
<?php
$Model = $this->getModel();
echo $Model->LoadFields();
echo JHtml::_('form.token');
?>
<input type="hidden" name="id" value="<?php echo (int) $this->item->id; ?>" />
<input type="hidden" name="task" value="edit.apply" />
<div class="btn-group">
<button class="btn btn-primary validate"><?php echo JText::_('JSAVE'); ?></button>
</div>
</form>