<?php
require_once PROJPATH . 'application/post/models/Post.php';

class PostScaffoldingController extends ScaffoldController {
	
	public function PostScaffoldingController(){
		parent::ScaffoldController();
		
		self::$objectName = "Post";
		self::$per_page = 5;
	}
	
	
}