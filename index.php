<?php 

session_start();

define("PROJPATH", __DIR__ . DIRECTORY_SEPARATOR);
define("APPPATH", PROJPATH . "application" . DIRECTORY_SEPARATOR);
if(php_sapi_name() == "cli") {
	define("__ENVIRONMENT__", "cli");
} else {
	define("__ENVIRONMENT__", "browser");
}

include PROJPATH . 'config/main.php';
foreach (glob( PROJPATH . "system/*.php") as $filename) {
	include $filename;
}

$router = new Router();

try {
	$controller_file = $router->getControllerFile();
	$controller_class = $router->getControllerClass();
	$action = $router->getAction();
	 
	
	require_once $controller_file;
	
	$c = new $controller_class;
	
	if( method_exists($c, $action) ) {
		call_user_func(array($c, $action));
	} else {
		throw new NotFoundException("");
	}
	
	
} catch ( NotFoundException $e) {
	
	$tpl = new Tpl();
	$tpl->display("home/views/404.html");
	
} catch ( Exception $e ) {
	
	print $e->getMessage();
	
} 




?>