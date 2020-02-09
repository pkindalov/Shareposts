<?php
class Users extends Controller
{
    public function __construct()
    {
        $this->userModel = $this->model('User');
        $this->likeModel = $this->model('Like');
        $this->postModel = $this->model('Post');
    }

    public function register()
    {
        //Check for post
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Proces form

            //Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            //Validate Name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }
            //Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                //Check email
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'This email is already taken. Please enter another.';
                }
            }

            //Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter passord';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            //Validate Confirm Password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if ($data['password'] !== $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords don\'t match';
                }
            }

            //Make sure errors are empty
            if (
                empty($data['email_err']) &&
                empty($data['name_err']) &&
                empty($data['password_err']) &&
                empty($data['confirm_password_err'])
            ) {
                //Validated

                //Hasing password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                //Register user
                if ($this->userModel->register($data)) {
                    flash('register_success', 'You are registered successfully. You can login in your profile now');
                    redirect('users/login');
                } else {
                    die('Something goes wrong');
                }
                // die('SUCCESS');
            } else {
                $this->view('users/register', $data);
            }
        } else {
            //Init data
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            //Load view
            $this->view('users/register', $data);
        }
    }

    public function login()
    {
        //Check for post
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Proces form
            //Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];

            //Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }


            //Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter passord';
            }

            //Check for user/email
            if ($this->userModel->findUserByEmail($data['email'])) {
                //User found
            } else {
                $data['email_err'] = 'No user found.';
            }

            //Make sure errors are empty
            if (
                empty($data['email_err']) &&
                empty($data['password_err'])
            ) {
                //Validated
                //Check and set logged users
                $loggedUser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedUser) {
                    //Create Session
                    $this->createUserSession($loggedUser);
                } else {
                    $data['password_err'] = 'Email or Password incorrect';
                    $this->view('users/login', $data);
                }
            } else {
                $this->view('users/login', $data);
            }
        } else {
            //Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
            ];

            //Load view
            $this->view('users/login', $data);
        }
    }


    public function listUsers($page)
    {
        if (!isset($page) || !$page) {
            $page = 1;
        }

        $page = (int) $page;
        $pageSize = 10;
        $users = $this->userModel->getUsersPaginated($page, $pageSize);

        if ($_SESSION['role'] == 'admin') {
            $notApprovedPostsCount = $this->postModel->getCountNotApprovedPostsYet();

            $data = [
                'users' => $users,
                'page' => (int) $page,
                'hasNextPage' => count($users) > 0,
                'hasPrevPage' => $page > 1,
                'nextPage' => $page + 1,
                'prevPage' => $page - 1,
                'notApprovedPostsCount' => $notApprovedPostsCount->count
            ];


            $this->view('users/listUsers', $data);
            return;
        }

        $data = [
            'users' => $users,
            'page' => $page,
            'hasNextPage' => count($users) > 0,
            'hasPrevPage' => $page > 1,
            'nextPage' => $page + 1,
            'prevPage' => $page - 1
        ];

        $this->view('users/listUsers', $data);
    }


    public function userProfile($userId)
    {
        $userId = htmlspecialchars($userId);
        $userId = (int) $userId;
        $user = $this->userModel->getUserById($userId);
        $userPostsCount = $this->userModel->getUserPostsCount($userId);
        $lastUserPosts = $this->userModel->getLastPosts($userId, 2);
        $likedPostsCount = $this->likeModel->getCountOfTheUserLikedPosts($userId);

        if ($_SESSION['role'] == 'admin') {
            $notApprovedPostsCount = $this->postModel->getCountNotApprovedPostsYet();

            $data = [
                'user' => $user,
                'postsCount' => $userPostsCount,
                'posts' => $lastUserPosts,
                'userLikedPostsCount' => $likedPostsCount->userLikedPostsCount,
                'notApprovedPostsCount' => $notApprovedPostsCount->count
            ];


            $this->view('users/userProfile', $data);
            return;
        }



        $data = [
            'user' => $user,
            'postsCount' => $userPostsCount,
            'posts' => $lastUserPosts,
            'userLikedPostsCount' => $likedPostsCount->userLikedPostsCount
        ];

        $this->view('users/userProfile', $data);
    }

    public function getUserPosts($url)
    {
        // print_r($url);
        $userId = extractUserId($url);
        $page = extractPageNum($url);
        $pageSize = 10;
        $userPosts = $this->userModel->getUsersPostWithPag($userId, $page, $pageSize);
        $userName =  $this->userModel->getUserById($userId);

        if ($_SESSION['role'] == 'admin') {
            $notApprovedPostsCount = $this->postModel->getCountNotApprovedPostsYet();

            $data = [
                'posts' => $userPosts,
                'page' => $page,
                'userId' => $userId,
                'userName' => $userName,
                'hasNextPage' => count($userPosts) > 0,
                'hasPrevPage' => $page > 1,
                'nextPage' => $page + 1,
                'prevPage' => $page - 1,
                'notApprovedPostsCount' => $notApprovedPostsCount->count
            ];


            $this->view('users/posts', $data);
            return;
        }

        $data = [
            'posts' => $userPosts,
            'page' => $page,
            'userId' => $userId,
            'userName' => $userName,
            'hasNextPage' => count($userPosts) > 0,
            'hasPrevPage' => $page > 1,
            'nextPage' => $page + 1,
            'prevPage' => $page - 1
        ];
        // print_r($data);

        // echo $queryStr;
        // var_dump(explode('&', $url));
        $this->view('users/posts', $data);
    }

    public function getLikedUserPosts($url)
    {
        // print_r($url);
        $userId = extractUserId($url);
        $page = extractPageNum($url);
        $pageSize = 10;
        $likedUserPosts = $this->userModel->getUserLikedPostWithPag($userId, $page, $pageSize);
        $userName =  $this->userModel->getUserById($userId);

        if ($_SESSION['role'] == 'admin') {
            $notApprovedPostsCount = $this->postModel->getCountNotApprovedPostsYet();

            $data = [
                'posts' => $likedUserPosts,
                'page' => $page,
                'userId' => $userId,
                'userName' => $userName,
                'hasNextPage' => count($likedUserPosts) > 0,
                'hasPrevPage' => $page > 1,
                'nextPage' => $page + 1,
                'prevPage' => $page - 1,
                'notApprovedPostsCount' => $notApprovedPostsCount->count
            ];


            $this->view('users/listLikedPosts', $data);
            return;
        }

        $data = [
            'posts' => $likedUserPosts,
            'page' => $page,
            'userId' => $userId,
            'userName' => $userName,
            'hasNextPage' => count($likedUserPosts) > 0,
            'hasPrevPage' => $page > 1,
            'nextPage' => $page + 1,
            'prevPage' => $page - 1
        ];
        // print_r($data);

        // echo $queryStr;
        // var_dump(explode('&', $url));
        $this->view('users/listLikedPosts', $data);
    }

    public function showUser($userId)
    {
        $this->userProfile($userId);
    }


    public function createUserSession($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['role'] = $user->role;
        redirect('posts');
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();
        redirect('users/login');
    }
}
