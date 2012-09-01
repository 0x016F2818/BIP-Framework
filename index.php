<?php 

error_reporting(E_ALL);

session_start();

define("PROJPATH", __DIR__ . DIRECTORY_SEPARATOR);
define("APPPATH", PROJPATH . "application" . DIRECTORY_SEPARATOR);

include PROJPATH . 'config/main.php';
foreach (glob( PROJPATH . "system/*.php") as $filename) {
	include $filename;
}

$uri = new Uri();
$conf = new Config();
$controller = ( $uri->segment(1) ) ? 
				strtolower($uri->segment(1)) : 
				$conf->item("main", "default_controller");
$action = ( $uri->segment(2) ) ? 
				strtolower($uri->segment(2)) : 
				"index";

$controllers = buildControllerIndex();
try {
	
	if(isset($controllers[$controller])) {
		
		require_once $controllers[$controller];
		$class_name = pathinfo($controllers[$controller], PATHINFO_FILENAME);
		
		$c = new $class_name;
		if( method_exists($c, $action) ) {
			call_user_func(array($c, $action));
		} else {
			throw new NotFoundException("");
		}
	} else {
		throw new NotFoundException("");	
	}
	
} catch ( NotFoundException $e) {
	
	$tpl = new Tpl();
	$tpl->display("home/views/404.html");
	
} catch ( Exception $e ) {
	
	print $e->getMessage();
	
} 



function buildControllerIndex(){
	$controllers_index = array();
	
	$files = scandir(PROJPATH . "application/");
	foreach($files as $f) {
		$dir = PROJPATH . "application/" . $f;
		if(in_array($f, array(".", "..")) == false) {
			$controllers_dir = $dir . "/controllers/";
			if(is_dir($controllers_dir)) {
				$controllers = scandir($controllers_dir);
				foreach($controllers as $c) {
					$file = $controllers_dir . $c;
					if(is_file($file) && in_array($c, array(".", "..")) == false) {

						$key = strtolower(str_replace("Controller", "", pathinfo($file, PATHINFO_FILENAME)));
						$controllers_index[$key] = $file;
					}
				}		
			}
		}
	}
	return $controllers_index;
}

?>