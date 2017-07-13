<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.modellist');

class QatDatabaseModelItems extends JModelList {
	public function __construct($config = array()) {
		if(empty($config['filter_fields'])) {
			$config['filter_fields'] = array('id', 'catid', 'created', 'publish_up', 'publish_down', 'published');
		}
		parent::__construct($config);
	}
	
	protected function getListQuery() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($this->getState('list.select', 'item.id, item.alias, item.published, item.created, item.publish_up, item.publish_down, item.catid'))->from('#__qatdatabase_items AS item');
		$search = $this->getState('filter.search');
		$published = $this->getState('filter.published');
		$catid = $this->getState('filter.catid');
		
		if (is_numeric($catid)) {
			$type = $this->getState('filter.catid.include', true) ? '= ' : '<> ';
			$SubCatsInc = $this->getState('filter.subcategories', false);
			$EqCat = 'catid ' . $type . (int) $catid;
			if ($SubCatsInc) {
				$levels = (int) $this->getState('filter.max_category_levels', '1');
				$subCatQuery = $db->getQuery(true);
				$subCatQuery->select('sub.id')->from('#__categories as sub')->join('INNER', '#__categories as this ON sub.lft > this.lft AND sub.rgt < this.rgt')->where('this.id = ' . (int) $catid)->where('sub.level <= this.level + ' . $levels);
				$query->where('(' . $EqCat . ' OR a.catid IN (' . $subCatQuery->__toString() . '))');
			} else {
				$query->where($EqCat);
			}
		}
		
		if(is_numeric($published)) {
			$query->where('item.published = ' . (int) $published);
		} elseif ($published == '') {
			$query->where('(item.published IN (0,1))');
		}
		
		$orderCol = $this->state->get('list.ordering', 'item.id');
		$orderDirn = $this->state->get('list.direction', 'asc');
		$query->select('c.title AS category_title')->join('LEFT', '#__categories as c ON c.id = item.catid');
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
	
	public function GetCategoriesNumber() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id')->from('#__categories')->where('extension=\'com_qatdatabase\' AND published=\'1\'');
		$db->setQuery($query);
		return count($db->loadObjectList());
	}
	
	public function GetCategories($catsId) {
		$db = JFactory::getDBO();
		$cats = explode(',', $catsId);
		ob_start();
		
		foreach($cats as $cat) {
			$query = $db->getQuery(true);
			$query->select($db->quoteName('title'))->from('#__categories')->where('id=\'' . $cat . '\' AND published=\'1\'');
			$db->setQuery($query);
			$rows = $db->loadResult() . ', ';
			echo $rows;
		}
		
		// Clean ob.
		$clean = ob_get_clean();
		echo rtrim($clean, ', ');
	}
}