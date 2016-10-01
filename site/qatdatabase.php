<?php
// No direct access to this file
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.controller');
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_qatdatabase/css/style.css', 'text/css', 'screen');
$controller = JControllerLegacy::getInstance('QatDatabase');
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();
?>
