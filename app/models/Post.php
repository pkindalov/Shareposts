<?php
    class Post{
        private $db;

        public function __construct()
        {
            $this->db = new Database;
        }

        public function getPosts(){
            $this->db->query("SELECT *, 
                                posts.id AS postId, 
                                users.id AS userId,
                                posts.created_at AS postCreated,
                                users.created_at AS userRegistered 
                              FROM posts 
                              INNER JOIN users 
                              ON posts.user_id = users.id
                              ORDER BY posts.created_at DESC
                              ");
            $results = $this->db->resultSet();

            return $results;
        }

        public function addPost($post){
            $this->db->query('INSERT INTO posts (title, body, user_id) 
                              VALUES (:title, :body, :user_id)
            ');

            $this->db->bind(':title', $post['title'], null);
            $this->db->bind(':body', $post['body'], null);
            $this->db->bind(':user_id', $post['user_id'], null);

            //Execute
            if($this->db->execute()){
                return true;
            } else {
                return false;
            }

        }

    }