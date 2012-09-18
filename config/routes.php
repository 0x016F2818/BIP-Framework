<?php
require_once PROJPATH . 'system/Router.php';

$routes = array(

		"home" => new RouteController(
				new RouteControllerNew("home"),
				new RouteControllerReal("home"),
				array(
						new RouteAction(
								new RouteActionNew("welcome"),
								new RouteActionReal("index")
						),
						new RouteAction(
								new RouteActionNew("submitApp"),
								new RouteActionReal("submitPlugin")
						),
				)
		),

		"default" => new RouteController(
				new RouteControllerNew("home"),
				new RouteControllerReal("home"),
				array(
						new RouteAction(
								new RouteActionNew("index"),
								new RouteActionReal("index")
						)
				)
		),

		"404" => new RouteController(
				new RouteControllerNew("home"),
				new RouteControllerReal("home"),
				array(
						new RouteAction(
								new RouteActionNew("show404"),
								new RouteActionReal("show404")
						)
				)
		),
);









