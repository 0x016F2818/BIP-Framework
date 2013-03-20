<?php
spl_autoload_register( 
	
	function( $classname ) {
	
		require_once PROJPATH . 'system/Router.php';
		$router = new Router();
		$modelsIndex = $router->buildModelIndex();
		
		if( isset($modelsIndex[$classname]) ) {
			require_once $modelsIndex[$classname];
		}
		
		$projectionsIndex = $router->buildProjectionIndex();
		if( isset($projectionsIndex[$classname]) ) {
			require_once $projectionsIndex[$classname];
		}
	}
);