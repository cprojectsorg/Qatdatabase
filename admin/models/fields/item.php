<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldItem extends JFormFieldList {
	protected $type = 'Item';
	protected function getOptions() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id,title,published,publish_up,publish_down,catid');
		$query->from('#__qatdatabase_items');
		$db->setQuery((string)$query);
		$items = $db->loadObjectList();
		$options = array();
		if($items) {
			foreach($items as $item) {
				$options[] = JHtml::_('select.option', $item->id, $item->title, $item->published, $item->catid);
			}
		}
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}
?>