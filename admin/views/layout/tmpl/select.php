<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->SideBarMenus; ?>
</div>
<div id="j-main-container" class="span10">
	<h2><?php echo JText::_('COM_QATDATABASE_LAYOUT_SELECT_LAYOUT_TO_EDIT'); ?> :</h2>
	<h4><a href="<?php echo JRoute::_('index.php?option=com_qatdatabase&view=layout&layout=postform'); ?>">Post form layout</a></h4>
	<h4><a href="<?php echo JRoute::_('index.php?option=com_qatdatabase&view=layout&layout=itemslist'); ?>">Items list layout</a></h4>
	<h4><a href="<?php echo JRoute::_('index.php?option=com_qatdatabase&view=layout&layout=item'); ?>">Item layout</a></h4>
</div>