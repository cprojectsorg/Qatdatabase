<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.modellist');
class QatDatabaseModelItems extends JModelList {
	public function __construct($config = array()) {
		if(empty($config['filter_fields'])) {
			$config['filter_fields'] = array('id', 'catid', 'title', 'created', 'publish_up', 'publish_down', 'published');
		}
		parent::__construct($config);
	}
	
	protected function getListQuery() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($this->getState('list.select', 'item.id, item.title, item.alias, item.published, item.created, item.publish_up, item.publish_down, item.catid'))->from('#__qatdatabase_items AS item');
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
		
		$orderCol = $this->state->get('list.ordering', 'item.title');
		$orderDirn = $this->state->get('list.direction', 'asc');
		$query->select('c.title AS category_title')->join('LEFT', '#__categories as c ON c.id = item.catid');
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
	
	/*public function GetCatName($catId) {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('title'))->from('#__categories')->where('id=\'' . $catId . '\'');
		$db->setQuery($query);
		$Catjson = json_encode($db->loadObjectList());
		$CatDejson = json_decode($Catjson, true);
		foreach($CatDejson as $key => $CatJvalue) {
			foreach ($CatJvalue as $key => $CatJvalueArr) {
				echo $CatJvalueArr;
			}
		}
	}*/
}
