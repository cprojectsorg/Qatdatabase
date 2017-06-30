<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

foreach($this->items as $key => $items) {
	echo "<div class=\"" . $this->cssPrefix . "item\">";
		echo "<div class=\"" . $this->cssPrefix . "item-top\">";
			echo "<h2><a href=\"" . JRoute::_('index.php?option=com_qatdatabase&view=item&id=' . $items->id) . "\">" . $items->title . "</a></h2>";
			echo "<a href=\"" . JRoute::_('index.php?option=com_qatdatabase&view=edit&id=' . $items->id) . "\">" . JText::_('COM_QATDATABASE_ITEM_EDIT_BUTTON') . "</a>";
			if($this->config->get('show_favorites_icon', '1') !== '0' || $this->config->get('show_share_icons', '1') !== '0') {
				echo "<div class=\"" . $this->cssPrefix . "share-icons-" . JFactory::getDocument()->direction . ' ' . $this->cssPrefix . "share-icons\">";
					if($this->config->get('show_share_icons', '1') == '1') {
						echo "<a href=\"http://www.facebook.com/sharer.php?u=" . JRoute::_('index.php?option=com_qatdatabase&view=item&id=' . $items->id, true, -1) . "\" target=\"_blank\"><img class=\"hasTooltip\" title=\"" . JText::_('COM_QATDATABASE_ITEM_SHARE_ON_FACEBOOK') . "\" src=\"media/com_qatdatabase/images/facebook.png\" /></a>";
						echo "<a href=\"http://twitter.com/share?url=" . JRoute::_('index.php?option=com_qatdatabase&view=item&id=' . $items->id, true, -1) . "&text=" . urlencode($items->title) . "&hashtags=#hashtag\" target=\"_blank\"><img class=\"hasTooltip\" title=\"" . JText::_('COM_QATDATABASE_ITEM_SHARE_ON_TWITTER') . "\" src=\"media/com_qatdatabase/images/twitter.png\" /></a>";
						echo "<a href=\"https://plus.google.com/share?url=" . JRoute::_('index.php?option=com_qatdatabase&view=item&id=' . $items->id, true, -1) . "\" target=\"_blank\"><img class=\"hasTooltip\" title=\"" . JText::_('COM_QATDATABASE_ITEM_SHARE_ON_GOOGLE_PLUS') . "\" src=\"media/com_qatdatabase/images/g+.png\" /></a>";
					}
					
					if($this->config->get('show_favorites_icon', '1') == '1') {
						echo "<a target=\"_blank\"><img class=\"hasTooltip\" title=\"" . JText::_('COM_QATDATABASE_ITEM_ADD_TO_FAVORITES') . "\" src=\"media/com_qatdatabase/images/favicon.png\" /></a>";
					}
				echo "</div>";
			}
			
		echo "</div>";
		echo "<div class=\"" . $this->cssPrefix . "item-content\">" . $items->content . "</div>";
	echo "</div>";
}