<?php
defined('_JEXEC') or die;

function QatDatabaseBuildRoute(&$query) {
	$segments = array();
	if(isset($query['view'])) {
		$segments[] = $query['view'];
		unset($query['view']);
	}
	
	if(isset($query['id'])) {
		$segments[] = $query['id'];
		unset($query['id']);
	}
	return $segments;
}

function QatDatabaseParseRoute($segments) {
	$vars = array();
	$count = count($segments);
	
	if ($count == '1') {
		$vars['view']   = $segments[0];
		$vars['id'] = $segments[1];
	}
	
	if ($count == '2') {
		$vars['view']   = $segments[0];
		$vars['id'] = $segments[1];
	}
	
	if ($count == '3') {
		$vars['view']   = $segments[0];
		$vars['id'] = $segments[2];
	}
	return $vars;
}
