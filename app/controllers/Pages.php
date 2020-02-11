<?php
class Pages extends Controller
{
    public function __construct()
    {
        $this->postModel = $this->model('Post');
        $this->commentModel = $this->model('Comment');
    }

    public function index()
    {

        if (isLoggedIn()) {
            redirect('/posts');
        }

        $data = [
            'title' => 'Php Simple Framework',
            'description' => 'Simple social network built on Php simple MVC framework'
        ];
        $this->view('pages/index', $data);
    }

    public function about()
    {

        if ($_SESSION['role'] == 'admin') {
            $notApprovedPostsCount = $this->postModel->getCountNotApprovedPostsYet();
            $notApprovedCommentsCount = $this->commentModel->getCountNotApprovedCommentsYet();

            $data = [
                'title' => 'About Us',
                'description' => 'App to share posts with other users',
                'notApprovedPostsCount' => $notApprovedPostsCount->count,
                'notApprovedCommentsCount' => $notApprovedCommentsCount->count
            ];


            $this->view('pages/about', $data);
            return;
        }

        $data = [
            'title' => 'About Us',
            'description' => 'App to share posts with other users'
        ];
        $this->view('pages/about', $data);
    }
}
