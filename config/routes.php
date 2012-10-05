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
		
		"members" => new RouteController(
			new RouteControllerNew("members"),
			new RouteControllerReal("users"),
			array(
				new RouteAction(
					new RouteActionNew("getMember"), 
					new RouteActionReal("getUser")		
				),
				new RouteAction(
					new RouteActionNew("getMemberData"),
					new RouteActionReal("getUserData")		
				)
			)
		),
		
		"users" => new RouteController(
				new RouteControllerNew("users"),
				new RouteControllerReal("home"),
				array(
					new RouteAction( 
						new RouteActionNew("index"), 
						new RouteActionReal("show404")
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









