<?php

class Router {
	
	protected $controller;
	protected $action;
	
	private $Config;
	private $Uri;
	
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