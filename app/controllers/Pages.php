<?php
class Pages extends Controller
{
    public function __construct()
    {
        $this->postModel = $this->model('Post');
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

            $data = [
                'title' => 'About Us',
                'description' => 'App to share posts with other users',
                'notApprovedPostsCount' => $notApprovedPostsCount->count
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
