<?php
abstract class Controller {
	
	protected $Config;
	protected $Tpl;
	protected $Uri;
	
	public function Controller(){
		$this->Config = new Config();
		$this->Tpl = new Tpl();
		$this->Uri = new Uri();
		
		$this->Tpl->assign("site_url", $this->Config->item("main", "site_url"));
		$default = $this->Config->item("routes", "default");
		
		$this->Tpl->assign(
				"controller", 
				( $this->Uri->segment(1) ) ? 
					strtolower($this->Uri->segment(1)) : 
					$default->getRealController()
		);
		$this->Tpl->assign(
				"action",
				( $this->Uri->segment(2) ) ?
					strtolower($this->Uri->segment(2)) :
					"index"
		);
	}
	
	
	/**
	 * redirect
	 *
	 * @access   public
	 * @param    string		$to
	 * @param	array		$params
	 * @return   void
	 */
	public function redirect($to, $params = null){
		
		$param_string = '';
		if($params !== null && is_array($params)) {
			foreach($params as $key => $val)
				$param_string .= "$key/$val/";
		}
		 
		if($to[0] == "/") {
			$site_url = $this->Config->item('main', 'site_url');
			$to = $site_url . ltrim($to, '/');
		}
		 
		header("Location: $to". $param_string, true, 302);
		return;
	}
	
}