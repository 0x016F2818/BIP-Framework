<?php
class Config {

	protected $configs;
	
	public function Config(){
		$this->configs = array();
	}
	
	public function load($file){
		include PROJPATH . "config/$file.php";
		return $$file;
	}
	
	public function item($file, $key){
		
		if(isset($this->configs[$file]) ) {
			if( isset($this->configs[$file][$key]) ) {
				return $this->configs[$file][$key];
			} else {
				throw new Exception("Could not find configuration option");
			}
		} elseif( is_file( PROJPATH . "config/" . $file . ".php") ) {
			include PROJPATH . 'config/' . $file . ".php";
			$this->configs[$file] = $$file;
			return $this->item($file, $key);
		} else {
			throw new Exception("Could not find configuration option");
		}
	}
		
	
}