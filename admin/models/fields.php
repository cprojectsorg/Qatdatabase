<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.modellist');

class QatDatabaseModelFields extends JModelList {
	public function __construct($config = array()) {
		if(empty($config['filter_fields'])) {
			$config['filter_fields'] = array('id', 'field.id', 'title', 'field.title', 'name', 'field.name', 'created', 'field.created', 'published', 'field.published', 'publish_up', 'field.publish_up', 'publish_down', 'field.publish_down', 'catid', 'field.catid', 'type', 'field.type', 'required', 'field.required', 'editable', 'field.editable');
		}
		parent::__construct($config);
	}
	
	protected function getListQuery() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($this->getState('list.select', 'field.id, field.title, field.name, field.created, field.published, field.publish_up, field.publish_down, field.catid, field.type, field.required, field.editable'))->from('#__qatdatabase_fields AS field');
		$search = $this->getState('filter.search');
		
		if(!empty($search)) {
			$like = $db->quote('%' . $search . '%');
			$query->where('field.title LIKE' . $like);
		}
		
		$published = $this->getState('filter.published');
		$catid = $this->getState('filter.catid');
		$type = $this->getState('filter.type');
		
		if(is_numeric($type)) {
			$query->where('field.type=' . $type);
		}
		
		if(is_numeric($catid)) {
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
			$query->where('field.published = ' . (int) $published);
		} elseif ($published == '') {
			$query->where('(field.published IN (0,1))');
		}
		
		$orderCol = $this->state->get('list.ordering', 'field.title');
		$orderDirn = $this->state->get('list.direction', 'asc');
		
		$query->select('c.title AS category_title')->join('LEFT', '#__categories as c ON c.id = field.catid');
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
	
	public function GetCategoriesNumber() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id')->from('#__categories')->where('extension=\'com_qatdatabase\'');
		$db->setQuery($query);
		return count($db->loadObjectList());
	}
	
	public function GetCategories($catsId) {
		$db = JFactory::getDBO();
		$cats = explode(',', $catsId);
		ob_start();
		foreach($cats as $cat) {
			$query = $db->getQuery(true);
			$query->select($db->quoteName('title'))->from('#__categories')->where('id=\'' . $cat . '\'');
			$db->setQuery($query);
			$rows = $db->loadResult() . ', ';
			echo $rows;
		}
		
		// Clean ob.
		$clean = ob_get_clean();
		echo rtrim($clean, ', ');
	}
	
	public function GetFieldType($num) {
		$array = array('1' => 'Check Box (Multiple)', '2' => 'Check Box (Single)', '3' => 'Date', '4' => 'Drop Down (Multiple Selection)', '5' => 'Drop Down (Single Selection)', '6' => 'Email address', '7' => 'Number text', '8' => 'Price field', '9' => 'Text area or editor', '10' => 'Text Field', '11' => 'URL (Link)', '12' => 'Radio', '13' => 'File', '14' => 'Images uploader');
		return $array[$num];
	}
	
	protected function populateState($ordering = null, $direction = null) {
		parent::populateState('field.title', 'ASC');
	}
}
?>
