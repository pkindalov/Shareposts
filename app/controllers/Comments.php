<?php
    class Comments extends Controller{
        public function __construct()
        {
            $this->commentModel = $this->model('Comment');
        }

        public function addCommentToPost($postId){

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Sanitize post
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $postId = htmlspecialchars($postId);
    
                $data = [
                    'text' => trim($_POST['text']),
                    'user_id' => $_SESSION['user_id'],
                    'post_id' => $postId,
                    'text_err' => '',
                ];
    
                //Validate data
                if (empty($_POST['text'])) {
                    $data['text_err'] = 'Comment cannot be empty';
                }
    
                //Make sure no errors
                if (empty($data['text_err'])) {
                    //Validated
                    if ($this->commentModel->addComment($data)) {
                        flash('comment_message', 'Comment added');
                        redirect('posts');
                    } else {
                        die('Something wrong with adding comment');
                    }
                } else {
                    //Redirect to same view but with errors
                    $this->view('comments/add', $data);
                }
            } else {
    
                $data = [
                    'text' => '',
                    'post_id' => $postId
                ];
                $this->view('comments/add', $data);
            }

            // $data = [
            //     'postId' => $postId
            // ];

            // $this->view('comments/add', $data);
        }
    }