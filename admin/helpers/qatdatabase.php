<?php
defined('_JEXEC') or die;
class QatDatabaseHelper extends JHelperContent {
	public static $extension = 'com_qatdatabase';
	public static function addSubmenu($vName) {
		JHtmlSidebar::addEntry(JText::_('COM_QATDATABASE_ITEMS_MENU'), 'index.php?option=com_qatdatabase&view=items', $vName == 'items');
		JHtmlSidebar::addEntry(JText::_('COM_QATDATABASE_FIELDS_MENU'), 'index.php?option=com_qatdatabase&view=fields', $vName == 'fields');
		JHtmlSidebar::addEntry(JText::_('COM_QATDATABASE_LAYOUT_CONTROL'), 'index.php?option=com_qatdatabase&view=layout', $vName == 'layout');
		JHtmlSidebar::addEntry(JText::_('COM_QATDATABASE_ITEMS_CATEGORIES'), 'index.php?option=com_categories&extension=com_qatdatabase', $vName == 'categories');
	}
	
}

function getFooter() {
	$xml = JFactory::getXML(JPATH_SITE . '/administrator/components/com_qatdatabase/com_qatdatabase.xml');
	$version = (string) $xml->version;
	
	echo "<div><div style=\"text-align: center; font-style: italic;\">" . JText::_('COM_QATDATABASE') . ", " . JText::_('COM_QATDATABASE_VERSION_TEXT') . ": <span style=\"font-weight: bold;\">" . $version . "</span>";
	echo "<div><a href=\"https://github.com/cprojectsorg/Qatdatabase/issues\" class=\"small\" style=\"font-style: italic;\" target=\"_blank\">" . JText::_('COM_QATDATABASE_REQUEST_OR_REPORT') . "<span style=\"margin-left: 1px; margin-top: 2px; font-size: 9px; vertical-align: top;\" class=\"icon-out-2 small\"></span></a></div>";
	echo "</div>";
}
?>
