<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.controllerform');
class QatDatabaseControllerLayout extends JControllerForm {
	public function getModel($name = 'Layout', $prefix = 'QatDatabaseModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	public function save($key = NULL, $urlVar = NULL) {
		JSession::checkToken() or die ('Invalid Token');
		$data = JRequest::getVar('jform', array(), 'post', 'array');
		
		if($data['type'] == 'postform') {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->insert('#__qatdatabase_fields_ordering');
			$query->columns('', 'ordering', 'fieldid');
			
			$OrdCount = 0;
			for($i = 1; $i <= count($data['qatdatabasefldid']); $i++) {
				$query->values('\'\', \'' . $i . '\', \'' . $data['qatdatabasefldid'][$OrdCount] . '\'');
				$OrdCount++;
			}
			
			$this->TruncateTable('#__qatdatabase_fields_ordering');
			$db->setQuery($query);
			$db->execute();
		}
		
		JFactory::getApplication()->enqueueMessage(JText::_('COM_QATDATABASE_ORDERING_SAVED'), 'message');
		$this->setredirect('index.php?option=com_qatdatabase&view=layout&layout=' . $data['type']);
	}
	
	protected function TruncateTable($Table) {
		$db = JFactory::getDBO();
		$db->setQuery('TRUNCATE TABLE `' . $Table . '`');
		$db->execute();
	}
}
?>
