<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldField extends JFormFieldList {
	protected $type = 'Field';
	
	protected function getOptions() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id,title,name,created,published,publish_up,publish_down,catid');
		$query->from('#__qatdatabase_fields');
		$db->setQuery((string)$query);
		$items = $db->loadObjectList();
		$options = array();
		
		if($items) {
			foreach($items as $item) {
				$options[] = JHtml::_('select.option', $item->id, $item->title, $item->name, $item->created, $item->published, $item->publish_up, $item->publish_down, $item->catid);
			}
		}
		
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}
?>
