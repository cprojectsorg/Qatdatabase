<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

if(!JFactory::getUser()->authorise('core.manage', 'com_qatdatabase')) {
	throw new JAccessExceptionNotallowed(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

// Load Joomla Bootstrap Tooltip.
JHtml::_('bootstrap.tooltip');

// Load Component's CSS file.
JFactory::getDocument()->addStyleSheet(JURI::base(true) . '/components/com_qatdatabase/css/qatdatabase.css');

$controller = JControllerLegacy::getInstance('QatDatabase');
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();
?>
