<?php
/*
 * @package    Qatdatabase
 * @copyright  Copyright (C) 2015 - 2017 cprojects.org. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class QatdatabaseRouter extends JComponentRouterView {
	public function __construct($app = null, $menu = null) {
		$this->registerView(new JComponentRouterViewconfiguration('item'));
		$this->registerView(new JComponentRouterViewconfiguration('items'));
		$this->registerView(new JComponentRouterViewconfiguration('edit'));
		
		parent::__construct($app, $menu);
		
		$this->attachRule(new JComponentRouterRulesMenu($this));
		$this->attachRule(new JComponentRouterRulesStandard($this));
		$this->attachRule(new JComponentRouterRulesNomenu($this));
	}
}

function QatdatabaseBuildRoute(&$query) {
	$app = JFactory::getApplication();
	$router = new QatdatabaseRouter($app, $app->getMenu());
	
	return $router->build($query);
}

function QatdatabaseParseRoute($segments) {
	$app = JFactory::getApplication();
	$router = new QatdatabaseRouter($app, $app->getMenu());
	
	return $router->parse($segments);
}