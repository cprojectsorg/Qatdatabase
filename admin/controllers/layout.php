<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.controlleradmin');
class QatDatabaseControllerLayout extends JControllerAdmin {
	public function getModel($name = 'Layout', $prefix = 'QatDatabaseModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
}
?>
