<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.controlleradmin');
class QatDatabaseControllerItems extends JControllerAdmin {
	public function getModel($name = 'Item', $prefix = 'QatDatabaseModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
}
?>
