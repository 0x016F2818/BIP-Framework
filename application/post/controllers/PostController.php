<?php
require_once PROJPATH . 'application/post/models/Post.php';

class PostController extends Controller {
	
	public function PostController(){
		parent::Controller();
	}
	
	public function index(){
		
		$posts = Post::getAll(null, null, array('date_created' => "desc"));
		
		$this->Tpl->assign("posts", $posts);
		$this->Tpl->assign('content', $this->Tpl->fetch("post/views/post/index.html"));
		$this->Tpl->display("home/views/main.html");
	}
	
	
	public function view(){
		$id = (int)$this->Uri->get_assoc("id", true);
		
		$post = new Post();
		$post->get($id);
		
		$this->Tpl->assign('post', $post);
		$this->Tpl->assign('content', $this->Tpl->fetch("post/views/post/view.html"));
		$this->Tpl->display("home/views/main.html");
	}
	
	
	public function add(){
		
		if(isset($_POST['submit'])) {
			
			$post = new Post();
			$post->title = $_POST['title'];
			$post->content = $_POST['content'];
			$post->date_created = time();
			$post->save();
			
			$this->redirect("/post/view/id/" . $post->id);
			
		}
		
		$this->Tpl->assign('content', $this->Tpl->fetch("post/views/post/add.html"));
		$this->Tpl->display("home/views/main.html");
	}
	
	
	public function edit(){
		
		$id = (int)$this->Uri->get_assoc("id", true);
		
		if(isset($_POST['submit'])) {
			$id = (int)$_POST['id'];
			
			$post = new Post();
			$post->get($id);
			$post->title = $_POST['title'];
			$post->content = $_POST['content'];
			$post->date_created = time();
			$post->save();
				
			$this->redirect("/post/view/id/" . $post->id);
				
		}
		$post = new Post();
		$post->get($id);
		
		$this->Tpl->assign("post", $post);
		$this->Tpl->assign('content', $this->Tpl->fetch("post/views/post/edit.html"));
		$this->Tpl->display("home/views/main.html");
	}
	
	
	public function delete(){
		
		$id = $this->Uri->get_assoc("id", true);
		$post = new Post();
		$post->get($id);
		$post->delete();
		
		$this->redirect("/post");
	}
	
	
}