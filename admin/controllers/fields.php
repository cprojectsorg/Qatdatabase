<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.controlleradmin');

class QatDatabaseControllerFields extends JControllerAdmin {
	public function __construct($config = array()) {
		parent::__construct($config);
		
		// Merge tasks.
		$this->registerTask('notrequired', 'required');
		$this->registerTask('noteditable', 'editable');
	}
	
	public function getModel($name = 'Field', $prefix = 'QatDatabaseModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	public function required() {
		$ids = $this->input->get('cid', array(), 'array');
		$values = array('required' => 1, 'notrequired' => 0);
		
		$value = JArrayHelper::getValue($values, $this->getTask(), 0, 'int');
		
		$model = $this->getModel();
		if(!$model->required($ids, $value)) {
			JError::raiseWarning(500, $model->getError());
		} else {
			
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_qatdatabase&view=fields', false));
	}
	
	public function editable() {
		$ids = $this->input->get('cid', array(), 'array');
		$values = array('editable' => 1, 'noteditable' => 0);
		
		$value = JArrayHelper::getValue($values, $this->getTask(), 0, 'int');
		
		$model = $this->getModel();
		if(!$model->editable($ids, $value)) {
			JError::raiseWarning(500, $model->getError());
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_qatdatabase&view=fields', false));
	}
}
?>