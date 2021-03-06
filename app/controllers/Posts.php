<?php

use function PHPSTORM_META\type;

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
        $this->commentModel = $this->model('Comment');
    }

    public function index()
    {
        redirect('posts/getPage/1');
        // $posts = $this->postModel->getPosts();
        // $data = [
        //     'posts' => $posts
        // ];

        // $this->view('posts/index', $data);
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

            $originalPostAuthor = $this->postModel->getAuthorOfPostByPostId($postId);
            $userId = $originalPostAuthor->user_id;
            $postContent = isAdmin() ? trim($_POST['body'] . " <p>Edited By Admin</p>") : trim($_POST['body']);


            $data = [
                'id' => $postId,
                'title' => trim($_POST['title']),
                'body' => $postContent,
                'user_id' => $userId,
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
            if ($post->user_id != $_SESSION['user_id'] && !isAdmin()) {
                redirect('posts');
            }

            if ($_SESSION['role'] == 'admin') {
                $notApprovedPostsCount = $this->postModel->getCountNotApprovedPostsYet();
                $notApprovedCommentsCount = $this->commentModel->getCountNotApprovedCommentsYet();

                $data = [
                    'id' => $postId,
                    'title' => $post->title,
                    'body' => $post->body,
                    'notApprovedPostsCount' => $notApprovedPostsCount->count,
                    'notApprovedCommentsCount' => $notApprovedCommentsCount->count
                ];


                $this->view('posts/edit', $data);
                return;
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
        $usersLikesPost = $this->postModel->getPeopleLikesPost($id);
        $user = $this->userModel->getUserById($post->user_id);
        $comments = $this->commentModel->getCommentsOfPostById($id);
        $data = [
            'post' => $post,
            'user' => $user,
            'likers' => $usersLikesPost,
        ];


        if ($_SESSION['role'] == 'admin') {
            $notApprovedPostsCount = $this->postModel->getCountNotApprovedPostsYet();
            $notApprovedCommentsCount = $this->commentModel->getCountNotApprovedCommentsYet();

            if (checkIfArrAndIfEmpty($comments)) {
                $data['notApprovedPostsCount'] = $notApprovedPostsCount->count;
                $data['notApprovedCommentsCount'] = $notApprovedCommentsCount->count;
                $data['comments'] = [];

                // $data = [

                //     'notApprovedPostsCount' => $notApprovedPostsCount->count,
                //     'notApprovedCommentsCount' => $notApprovedCommentsCount->count,
                //     'comments' => []
                // ];

                $this->view('posts/show', $data);
                return;
            }

            $data['notApprovedPostsCount'] = $notApprovedPostsCount->count;
            $data['notApprovedCommentsCount'] = $notApprovedCommentsCount->count;
            $data['comments'] = $comments;
            $this->view('posts/show', $data);
            return;
        }

        if (checkIfArrAndIfEmpty($comments)) {
            $data['comments'] = [];
            $this->view('posts/show', $data);
            return;
        }

        $data = [
            'post' => $post,
            'user' => $user,
            'likers' => $usersLikesPost,
            'comments' => $comments
        ];

        $this->view('posts/show', $data);
    }


    public function delete($postId)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = $this->postModel->getPostById($postId);

            //Check for owner
            if ($post->user_id != $_SESSION['user_id'] && !isAdmin()) {
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

    public function getPage($page)
    {

        if (!isset($page) || !$page) {
            $page = 1;
        }

        $page = (int) $page;
        $pageSize = 5;
        $posts = $this->postModel->getPostsPaginated($page, $pageSize);
        $currentUser = $_SESSION['user_id'];
        $countApprovedPosts = $this->postModel->countApprovedPostsByUserId($currentUser);
        $countNotApprovedPosts = $this->postModel->countNotApprovedPostsByUserId($currentUser);

        $data = [
            'posts' => $posts,
            'page' => (int) $page,
            'hasNextPage' => count($posts) > 0,
            'hasPrevPage' => $page > 1,
            'nextPage' => $page + 1,
            'prevPage' => $page - 1,
            'countApprovedPosts' => $countApprovedPosts,
            'countNotApprovedPosts' => $countNotApprovedPosts
        ];

        //to make query about not approved posts only if admin is logged
        if ($_SESSION['role'] == 'admin') {

            if (checkIfArrAndIfEmpty($posts)) {
                $data['posts'] = '';
                $this->view('posts/index', $data);
                return;
            }

            $notApprovedPostsCount = $this->postModel->getCountNotApprovedPostsYet();
            $notApprovedCommentsCount = $this->commentModel->getCountNotApprovedCommentsYet();

            $postsIds = $this->getPostsIds($posts);
            $comments = $this->commentModel->getCommentsByPostsIds($postsIds);



            $numOfComments = count((array) $comments);

            if ($numOfComments > 0) {
                for ($c = 0; $c < count($comments); $c++) {
                    for ($p = 0; $p < count($posts); $p++) {
                        if ($comments[$c]->post_id == $posts[$p]->postId) {
                            $posts[$p]->comment[] = $comments[$c];
                        }
                    }
                }
            }

            $data['notApprovedPostsCount'] = $notApprovedPostsCount->count;
            $data['notApprovedCommentsCount'] = $notApprovedCommentsCount->count;
            $this->view('posts/index', $data);
            return;
        }

        if (checkIfArrAndIfEmpty($posts)) {
            $data['posts'] = '';
            $this->view('posts/index', $data);
            return;
        }

        $postsIds = $this->getPostsIds($posts);
        $comments = $this->commentModel->getCommentsByPostsIds($postsIds);
        $numOfComments = count((array) $comments);

        if ($numOfComments > 0) {
            for ($c = 0; $c < count($comments); $c++) {
                for ($p = 0; $p < count($posts); $p++) {
                    if ($comments[$c]->post_id == $posts[$p]->postId) {
                        $posts[$p]->comment[] = $comments[$c];
                    }
                }
            }
        }


        $this->view('posts/index', $data);
    }

    public function postsForApproving($page)
    {
        if ($_SESSION['role'] != 'admin') {
            redirect('posts');
        }

        if (!isset($page) || !$page) {
            $page = 1;
        }
        $page = (int) $page;
        $pageSize = 15;
        $posts = $this->postModel->getPostsForApproving($page, $pageSize);
        $notApprovedPostsCount = $this->postModel->getCountNotApprovedPostsYet();
        $notApprovedCommentsCount = $this->commentModel->getCountNotApprovedCommentsYet();

        $data = [
            'posts' => $posts,
            'page' => (int) $page,
            'hasNextPage' => count($posts) > 0,
            'hasPrevPage' => $page > 1,
            'nextPage' => $page + 1,
            'prevPage' => $page - 1,
            'notApprovedPostsCount' => $notApprovedPostsCount->count,
            'notApprovedCommentsCount' => $notApprovedCommentsCount->count
        ];
        if (checkIfArrAndIfEmpty($posts)) {
            $data['posts'] = '';
            $this->view('posts/listPostsForApproving', $data);
            return;
        }
        $this->view('posts/listPostsForApproving', $data);
    }

    public function approvePost($postId)
    {
        if ($_SESSION['role'] != 'admin') {
            redirect('posts');
        }

        $postId = htmlspecialchars($postId);
        $this->postModel->approvePostById($postId);
        redirect('/posts/postsForApproving/1');
    }

    private function getPostsIds($posts)
    {
        $ids = [];
        foreach ($posts as $post) {
            $ids[] = $post->postId;
        }

        return $ids;
    }
}
