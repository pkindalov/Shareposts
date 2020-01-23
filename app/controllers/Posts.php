<?php
    class Posts extends Controller{

        public function __construct()
        {
            //Because of this check all methods here are for authenticated users. Else unauthorized user 
            //will be redirected. 
            //If you want some actions to not required user authentication then you must MOVE this check from here to 
            //methods which are requiring authorization
            if(!isLoggedIn()){
                redirect('users/login');
            }

            $this->postModel = $this->model('Post');
        }

        public function index(){
            $posts = $this->postModel->getPosts();
            $data = [
                'posts' => $posts
            ];
           
            $this->view('posts/index', $data);
        }
        
        public function add(){
            $data = [
                'title' => '',
                'body' => ''
            ];
            $this->view('posts/add', $data);
        }


    }