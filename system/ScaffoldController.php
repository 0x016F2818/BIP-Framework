<?php
class ScaffoldController extends Controller {
	
	public static $objectName;
	public static $index_template;
	public static $view_template;
	public static $add_template;
	public static $edit_template;
	public static $per_page;
	
	public function ScaffoldController(){
		parent::Controller();
		
		self::$index_template = "home/views/scaffold/index.html";
		self::$view_template = "home/views/scaffold/view.html";
		self::$add_template = "home/views/scaffold/add_edit.html";
		self::$edit_template = "home/views/scaffold/add_edit.html";
		self::$per_page = 20;
		
	}
	
	public function index(){
		$page = (int)$this->Uri->get_assoc('page');
		$sort_by = $this->Uri->get_assoc('sort_by');
		$order = $this->Uri->get_assoc('order');
		
		$order_by = null;
		if($sort_by != false) {
			if(!in_array(strtoupper($order), array('ASC', 'DESC'))) {
				$order = "ASC"; 
			}
			$order_by = array($sort_by => $order);
		}
		$limit = array($page*self::$per_page => self::$per_page);
		
		$obj_name = self::$objectName;
		$data = $obj_name::getAll(null, $limit, $order_by);
		if(!empty($data)) 
			foreach($data as &$d)
				$d = $d->toArray();
			
		$total_rows = $obj_name::getRowCount();
		
		if(count($data) < $total_rows) {
			$pagination = true;
			$num_pages = ceil($total_rows / self::$per_page);
			if($order_by != null) {
				$curr_params = "sort_by/" . current(array_keys($order_by)) . "/order/" . current($order_by);
			} else  {
				$curr_params = "";
			}
			
			$this->Tpl->assign('num_pages', $num_pages);
			$this->Tpl->assign('curr_params', $curr_params);
			$this->Tpl->assign('curr_page', $page);
		} else {
			$pagination = false;
		}
		
		
		$this->Tpl->assign('pagination', $pagination);
		if(!empty($data)) {
			$this->Tpl->assign('fields', array_keys($data[0]));
		} else { 
			$this->Tpl->assign('fields', array());
		}
		$this->Tpl->assign('data', $data);
		$this->Tpl->display(self::$index_template);
	}
	
	
	public function view(){
		$id = (int)$this->Uri->get_assoc('id', true);
		$data = new self::$objectName;
		$data->get($id);
		
		$this->Tpl->assign('data', $data->toArray());
		$this->Tpl->display(self::$view_template);
	}
	
	
	public function add(){
		
		if(isset($_POST['submit'])) {
			
			unset($_POST['submit']);
			
			if(isset($_POST['id'])) {
				$id = (int)$_POST['id'];
				unset($_POST['id']);
			}
			$obj = new self::$objectName;
			
			foreach($_POST as $key => $val)
				$obj->$key = $val;

			$obj->save();
			
			$this->redirect('/' . $this->Uri->segment(1) . "/view/id/" . $obj->id);
		}
		$data = new self::$objectName;
		//var_dump($data);
		
		$this->Tpl->assign('action', 'add');
		$this->Tpl->assign('data', $data->toArray());
		$this->Tpl->display(self::$add_template);
	}
	
	
	public function edit(){
		
		$id = (int)$this->Uri->get_assoc('id', true);
		
		if(isset($_POST['submit'])) {
			
			unset($_POST['submit']);
			
			if(isset($_POST['id'])) {
				$id = (int)$_POST['id'];
				unset($_POST['id']);
			}
			$obj = new self::$objectName;
			$obj->get($id);
			
			foreach($_POST as $key => $val)
				$obj->$key = $val;

			$obj->save();
			
			$this->redirect('/' . $this->Uri->segment(1) . "/view/id/" . $obj->id);
		}
		
		$data = new self::$objectName;
		$data->get($id);
		
		$this->Tpl->assign('action', 'edit');
		$this->Tpl->assign('data', $data->toArray());
		$this->Tpl->display(self::$edit_template);
	}
	
	
	public function delete(){
		$id = (int)$this->Uri->get_assoc('id', true);
		$data = new self::$objectName;
		$data->get($id);
		$data->delete();
		
		$this->redirect('/' . $this->Uri->segment(1) . "/index");
	}
}