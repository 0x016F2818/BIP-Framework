<?php

class HomeController extends Controller {
	
	public function HomeController(){
		parent::Controller();
	}
	
	public function index(){
		$this->Tpl->assign("content", $this->Tpl->fetch('home/views/welcome.html'));
		$this->Tpl->display('home/views/main.html');
	}
}