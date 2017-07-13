<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.controller');

// Check if DS is already defined.
if(!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

$document = JFactory::getDocument();
$document->addStyleSheet('components' . DS . 'com_qatdatabase' . DS . 'css' . DS . 'style.css', 'text/css', 'screen');

$controller = JControllerLegacy::getInstance('QatDatabase');
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();
?>