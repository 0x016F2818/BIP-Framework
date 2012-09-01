<?php
class Post extends Model {
	
	protected $id;
	protected $title;
	protected $content;
	protected $date_created;
	
	public function Post(){
		parent::Model();
	}
	
	public function getTable() {
		return "posts";
	}
}