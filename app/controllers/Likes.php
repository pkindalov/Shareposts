<?php
    class Likes extends Controller{
        public function __construct()
        {
            $this->likesModel = $this->model('Like');
        }

        public function addLikeToPost($postId){
           $postId = htmlspecialchars($postId); 
           $voted = $this->likesModel->checkUserVoted($postId); 
           if(!$voted){
                $this->likesModel->addLike($postId);
           }

           redirect('posts/getPage/1');
        }

        public function dislikePost($postId){
            $postId = htmlspecialchars($postId);
            $this->likesModel->dislikePost($postId);

            redirect('posts/getPage/1');
        }
    }