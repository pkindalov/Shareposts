<?php
class Posts extends Controller
{

    public function __construct()
    {
        //Because of this check all methods here are for authenticated users. Else unauthorized user 
        //will be redirected. 
        //If you want some actions to not required user authentication then you must MOVE this check from here to 
        //methods which are requiring authorization
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }

    public function index()
    {
        $posts = $this->postModel->getPosts();
        $data = [
            'posts' => $posts
        ];

        $this->view('posts/index', $data);
    }

    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Sanitize post
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'body_err' => ''
            ];

            //Validate data
            if (empty($_POST['title'])) {
                $data['title_err'] = 'Title cannot be empty';
            }

            if (empty($_POST['body'])) {
                $data['body_err'] = 'Body cannot be empty';
            }


            //Make sure no errors
            if (empty($data['title_err']) && empty($data['body_err'])) {
                //Validated
                if ($this->postModel->addPost($data)) {
                    flash('post_message', 'Post added');
                    redirect('posts');
                } else {
                    die('Something wrong with adding post');
                }
            } else {
                //Redirect to same view but with errors
                $this->view('posts/add', $data);
            }
        } else {

            $data = [
                'title' => '',
                'body' => ''
            ];
            $this->view('posts/add', $data);
        }
    }


    public function edit($postId)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Sanitize post
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $postId,
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'body_err' => ''
            ];

            //Validate data
            if (empty($_POST['title'])) {
                $data['title_err'] = 'Title cannot be empty';
            }

            if (empty($_POST['body'])) {
                $data['body_err'] = 'Body cannot be empty';
            }


            //Make sure no errors
            if (empty($data['title_err']) && empty($data['body_err'])) {
                //Validated
                if ($this->postModel->updatePost($data)) {
                    flash('post_message', 'Post updated');
                    redirect('posts');
                } else {
                    die('Something wrong with adding post');
                }
            } else {
                //Redirect to same view but with errors
                $this->view('posts/edit', $data);
            }
        } else {
            //Get existing posts from the model
            $post = $this->postModel->getPostById($postId);

            //Check for owner
            if ($post->user_id != $_SESSION['user_id']) {
                redirect('posts');
            }

            $data = [
                'id' => $postId,
                'title' => $post->title,
                'body' => $post->body
            ];
            $this->view('posts/edit', $data);
        }
    }

    public function show($id)
    {
        $post = $this->postModel->getPostById($id);
        $user = $this->userModel->getUserById($post->user_id);
        $data = [
            'post' => $post,
            'user' => $user
        ];

        $this->view('posts/show', $data);
    }


    public function delete($postId)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = $this->postModel->getPostById($postId);

            //Check for owner
            if ($post->user_id != $_SESSION['user_id']) {
                redirect('posts');
            }


            if ($this->postModel->deletePost($postId)) {
                flash('post_message', 'Post deleted');
                redirect('posts');
            }

            die('Something goes wrong with deleting post');
        }

        redirect('posts');
    }
}
