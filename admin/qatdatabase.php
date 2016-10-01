<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');

// Load Joomla Bootstrap Tooltip.
JHtml::_('bootstrap.tooltip');

// Load Component's CSS file.
JFactory::getDocument()->addStyleSheet(JURI::base(true) . '/components/com_qatdatabase/css/qatdatabase.css');

$controller = JControllerLegacy::getInstance('QatDatabase');
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();
?>
