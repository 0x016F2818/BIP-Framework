<?php

class Router {
	
	protected $controller;
	protected $action;
	
	private $Config;
	private $Uri;
	
	private static $fileIndexes = array();
	
	public function Router(){
		$this->Config = new Config();
		$this->Uri = new Uri();
		
		$this->controller = ($this->Uri->segment(1) != false) 
							? $this->Uri->segment(1) 
							: false;
		$this->action = ( $this->Uri->segment(2) != false)
							? $this->Uri->segment(2)
							: "index";
		
		
		if($this->controller == false) {
			$default = $this->Config->item("routes", "default");
			$this->controller = $default->getRealController();
		}
	}
	
	
	public function getControllerFile(){
		
		
		try {
			$routing = $this->Config->item("routes", $this->controller);
			$this->controller = $routing->getRealController();
			//var_dump($this->action);
			$this->action = $routing->findActionRoute($this->action);
			//var_dump($this->action); die();
		} catch ( Exception $e) {
			
		}
		
		
		
		$controllers = $this->buildControllerIndex();
		$controller_class = ucfirst($this->controller) . 'Controller';
		$controller_file = $controllers[$this->controller];
		
		if(file_exists($controller_file) == true) {
			return $controller_file;
		} else {
			$e404 = $this->Config->item("routes", "404");
			$this->controller = $e404->getRealController();
			$this->action = "show404";
			$controller_class = ucfirst($this->controller) . "Controller";
			$controller_file = $controllers[$this->controller];
			return $controller_file;
		}
		
		
	}
	
	public function getControllerClass(){
		$file_path = $this->getControllerFile();
		return pathinfo($file_path, PATHINFO_FILENAME);
		
	}
	
	public function getAction(){
		return $this->action;
	}
	
	
	public function buildControllerIndex(){
		
		$controllers = $this->getFileIndex("controllers");
		$retval = array();
		foreach($controllers as $key => $val) {
			$new_key = strtolower(str_replace("Controller", "", $key));
			$retval[$new_key] = $val; 
		}
		return $retval;
	}
	
	public function buildModelIndex(){
		return $this->getFileIndex("models");
	}
	
	public function buildProjectionIndex(){
		return $this->getFileIndex("projections");
	}
	
	private function getFileIndex( $folder, $lowercase_key = false){
		
		if(isset(self::$fileIndexes[$folder])) {
			return self::$fileIndexes[$folder];
		}
		
		$file_index = array();
		
		$files = scandir(APPPATH);
		foreach($files as $f) {
			$dir = APPPATH .  $f;
			if(in_array($f, array(".", "..")) == false) {
				$file_dir = $dir . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR;
				if(is_dir($file_dir)) {
					$models = scandir($file_dir);
					foreach($models as $c) {
						$file = $file_dir . $c;
						if(is_file($file) && in_array($c, array(".", "..")) == false) {
		
							$key = pathinfo($file, PATHINFO_FILENAME);
							if($lowercase_key == true) {
								$key = strtolower($key);
							}
							$file_index[$key] = $file;
						}
					}
				}
			}
		}
		self::$fileIndexes[$folder] = $file_index;
		return $file_index;
	}
	
	public function getControllerModuleDir($controller_class){
		$module_name = $this->getControllerModuleName($controller_class);
		return APPPATH . $module_name . DIRECTORY_SEPARATOR;
	}
	
	public function getControllerModuleName($controller_class){
		$module = strtolower( str_replace("Controller", "", $controller_class) );
		$index = (new Router())->buildControllerIndex();
		
		if(isset($index[$module])) {
			$controller_folder = $index[$module];
			$controller_folder_arr = explode(DIRECTORY_SEPARATOR, $controller_folder);
			$num_elems = count($controller_folder_arr);
			
			return $controller_folder_arr[$num_elems -3];
		}		
		
	}
	
}





class RouteController {

	protected $new;
	protected $real;
	protected $actions = array(); 

	public function RouteController( RouteControllerNew $new, RouteControllerReal $real, $actions = array()){
		$this->new = $new;
		$this->real = $real;
		$this->actions = $actions;
		
	}
	
	public function getRealController(){
		return $this->real->getName();
	}
	
	public function getActions(){
		return $this->actions;
	}
	
	public function findActionRoute($route_name){
		foreach($this->actions as $a) {
			if($a->getRouteName() == $route_name) {
				return $a->getRouteRealName();
			}
		}
		return $route_name;
	}
}

class RouteControllerNew {
	
	protected $name;
	
	public function RouteControllerNew($name){
		$this->name = $name;
	}
	
	public function getName(){
		return $this->name;
	}
}

class RouteControllerReal {
	
	protected $name;
	
	public function RouteControllerReal($name){
		$this->name = $name;
	}
	
	public function getName(){
		return $this->name;
	}
}



class RouteAction {
	
	protected $name;
	protected $reference;

	public function RouteAction(RouteActionNew $name, RouteActionReal $refercence){
		$this->name = $name;
		$this->reference = $refercence;
	}
	
	public function getRouteName(){
		return $this->name->getName();
	}
	
	public function getRouteRealName(){
		return $this->reference->getName();
	}
}

class RouteActionNew {
	
	protected $name;
	
	public function RouteActionNew($name){
		$this->name = $name;
	}
	
	public function getName(){
		return $this->name;
	}
}

class RouteActionReal {

	protected $name;

	public function RouteActionReal($name){
		$this->name = $name;
	}

	public function getName(){
		return $this->name;
	}
}