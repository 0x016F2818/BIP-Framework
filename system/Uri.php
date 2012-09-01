<?php
/**
 * @file         URI manager
 * @author       Hristo Georgiev
 * @contact      hristo@42-soft.com
 * @company      hgeorgiev.com
 * @license      check License.txt
 */
class Uri{
    
    /**
    * segment
    * returns uri segment
    * 
    * @access   public
    * @param    $uri_num    int
    * @return   string
    */
    public function segment($uri_num){
        $uri_num = (int)$uri_num - 1;     // we expect 1 for first URI, not 0
        
        $uris = $this->getUris();
        if($uris == false){
            return false;
        }
        
        if(isset($uris[$uri_num])){
            return $uris[$uri_num];
        }
        return false;

    }
    
    
    /**
    * get_array
    * returns uris in array; public wrapper
    * 
    * @access   public
    * @param    void
    * @return   array
    * 
    */
    public function get_array(){
        return $this->getUris();
    }
    
    
    /**
    * getParams
    * returns uri params without the controller and action pieces
    * 
    * @access   public
    * @param    void
    * @return   array
    */
    public function getParams(){
        $data = $this->getUris();
        if(count($data)>2){
            unset($data[0]);
            unset($data[1]);
        } else {
            $data = array();
            /* ugly hack, do not try this at home */
            for($i=0; $i<=20; $i++){
                $data[$i] = null;
            }
        }
        return $data;
    }
    
    
    /**
    * get_assoc
    * returns uri parameters structured in associative array
    * 
    * @access   public
    * @param    string	$key	
    * @param	bool	$mandatory
    * @return   array
    */
    public function get_assoc($key = null, $mandatory = false){
        
    	$uris = $this->getUris();
        
        $assoc_uris = array();
        
        $num_uris = count($uris);
        for($i=2; $i<$num_uris; $i+=2){
            if(isset($uris[$i+1])) {
                $assoc_uris[$uris[$i]] = $uris[$i+1];    
            } else {
                $assoc_uris[$uris[$i]] = null;
            }            
        }
        if($key != null){
        	if(isset($assoc_uris[$key])){
        		return $assoc_uris[$key];
        	} else {
        		if($mandatory == true){
        			throw new Exception('Cannot find URI ' . $key);
        		}
        		return false;
        	}
        }
        return $assoc_uris;
        
    }
	
    
    /**
    * getUris
    * handles the way uri segments are retrieved and filtered
    * 
    * @access   public
    * @param    void
    * @return   array
    */
	private function getUris(){
		$url = isset($_GET['url']) ? $_GET['url'] : false;
        if($url == false){
            return false;
        }
        $uris = explode('/', $url);
        foreach($uris as &$u){
            $u = filter_var($u, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $uris;
	}
	
	
	public function setUriString($string){
		$this->orig_uri = $_GET['url'];
		$_GET['url'] = $string;
		return;
	}
	
	public function restoreOriginalString(){
		$_GET['url'] = $this->orig_uri;
		return;
	}
	
    
    public function getSubdomain(){
    	$url =  explode('.', $_SERVER['SERVER_NAME']);
    	return $url[0];
    }
    
    
    public function getTld(){
    	$url = explode('.', $_SERVER['SERVER_NAME']);
    	return end($url);
    }
    
    
}
?>