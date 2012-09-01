<?php

class Tpl{
    
    private $vars;
    public $templates;
    public $libs;
    
    protected $Config;
    
	function Tpl(){
		$this->template_dir = APPPATH;
		
	}
    
    
    /**
    * __set
    * 
    * @access   public
    * @param    mixed $var
    * @param    mixed $value
    * @return   void
    */
    public function __set($var, $value){
        $this->vars[$var] = $value;
    }
    
    
    /**
    * __get
    * 
    * @access   public
    * @param    mixed $var
    * @return   void
    */
    public function __get($var){
        return $this->vars[$var];
    }
    
    
    /**
    * assign
    * 
    * @access   public
    * @param    string  $key
    * @param    mixed   $value
    * @return   void
    */
    function assign($key, $value){
         $this->__set($key, $value);
    }
    
    
    /**
    * fetch
    * 
    * @access   public
    * @param    string      $template
    * @return   string
    */
    function fetch($template){
        ob_start();
        $this->display($template);
        return ob_get_clean();
    }
    
    
    /**
    * display
    * 
    * @access   public
    * @param    string  $template
    */
    function display($template){
    	
        foreach($this->vars as $key=>$value){
            $$key = $value;
        }
        $tpl = $this;
        $this->templates[] = $template;
        
        if(is_file($this->template_dir . $template)){
            include $this->template_dir . $template;
        } else {
            throw new Exception('Cannot find template ' . $this->template_dir . $template);
        }
        

    }
    
    public function clearVars(){
    	$this->vars = array();
    	$this->template_dir = APPPATH;
    	
    }
    
    public function sec_to_time($time) {
    	$time = (int)$time;
    	
    	$hours = $time / 3600;
    	$minutes = ($time - floor($hours) * 3600 ) / 60;
    	$seconds = $time - (floor($hours) * 3600) - (floor($minutes) * 60);
    
    	$hours  =  str_pad(floor($hours), 2, 0, STR_PAD_LEFT);
    	$minutes = str_pad(floor($minutes), 2, 0, STR_PAD_LEFT);
    	$seconds = str_pad(floor($seconds), 2, 0, STR_PAD_LEFT);
    
    	$result = "$hours:$minutes:$seconds";
    	return $result;
    }
    
  
}