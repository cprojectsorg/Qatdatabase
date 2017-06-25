<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomlitem.application.component.modelitem');
class QatDatabaseModelItems extends JModelItem {
	protected $items;
	public function getItems() {
		if(!isset($this->items)) {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$NullDate = $db->Quote($db->getNullData());
			$CurrentDate = $db->Quote(JFactory::getDate()->toSql());
			$query->select('item.id, item.title, item.alias, item.content, item.published, item.created, item.publish_up, item.publish_down, item.catid, CASE WHEN CHAR_LENGTH(item.alias) THEN CONCAT_WS(":", item.id, item.alias) ELSE item.id END as slug');
			$query->from('#__qatdatabase_items AS item');
        	$query->where('item.published=\'1\'');
			$query->where('(item.publish_up=' . $NullDate . 'OR item.publish_up <=' . $CurrentDate . ')');
			$query->where('(item.publish_down=' . $NullDate . ' OR item.publish_down=\'0000-00-00 00:00:00\' OR item.publish_down >=' . $CurrentDate . ')');
			$query->order('item.id DESC');
			$db->setQuery((string)$query);
			$this->items = $db->loadObjectList();
		}
		return $this->items;
	}
}

