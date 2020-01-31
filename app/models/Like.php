<?php
    class Like{
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        public function addLike($postId){
            $this->db->query('INSERT INTO likes (user_id, post_id) VALUES ( :userId, :postId)' );
            $this->db->bind(':userId', $_SESSION['user_id'], null);
            $this->db->bind(':postId', $postId, null);

            if($this->db->execute()){
                return true;
            }

            return false;
        }

        public function checkUserVoted($postId){
            $this->db->query('SELECT user_id FROM likes WHERE likes.post_id = :postId');
            $this->db->bind(':postId', $postId, null);
            $row = $this->db->single();
            
            if($row && $_SESSION['user_id'] === $row->user_id){
              return true;
            }
            return false;
        }

        public function dislikePost($postId){
            $this->db->query("DELETE FROM likes WHERE post_id = :postId AND user_id = :userId");
            $this->db->bind(":postId", $postId, null);
            $this->db->bind(":userId", $_SESSION['user_id'], null);
            $this->db->execute();
        }

        public function getCountOfTheUserLikedPosts($userId){
            $this->db->query("SELECT COUNT(*) AS userLikedPostsCount FROM likes WHERE likes.user_id = :userId");
            $this->db->bind(":userId", $userId, null);
            $row = $this->db->single();
            return $row;
        }
    }