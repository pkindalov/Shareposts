<?php
class Post
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getPosts()
    {
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


    public function getPostsPaginated($page, $pageSize)
    {
        // echo gettype($page);
        if ($page <= 0) {
            $page = 1;
        }

        // $allRecords = $this->getPosts();
        $offset = ($page - 1) * $pageSize;

        // echo $offset;
        $this->db->query("SELECT *, 
                            posts.id AS postId, 
                            users.id AS userId,
                            posts.created_at AS postCreated,
                            users.created_at AS userRegistered 
                            FROM posts 
                            INNER JOIN users 
                            ON posts.user_id = users.id
                            ORDER BY posts.created_at DESC
                            LIMIT :limit
                            OFFSET :offset
      ");

        $this->db->bind(':limit', $pageSize, null);
        $this->db->bind(':offset', $offset, null);
        $results = $this->db->resultSet();
        // $total_pages = ceil(count($results) / $pageSize);

        return $results;
    }

    public function addPost($post)
    {
        $this->db->query('INSERT INTO posts (title, body, user_id) 
                              VALUES (:title, :body, :user_id)
            ');

        $this->db->bind(':title', $post['title'], null);
        $this->db->bind(':body', $post['body'], null);
        $this->db->bind(':user_id', $post['user_id'], null);

        //Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updatePost($post)
    {
        $this->db->query('UPDATE posts SET title = :title, body = :body WHERE id = :postId
            ');

        $this->db->bind(':postId', $post['id'], null);
        $this->db->bind(':title', $post['title'], null);
        $this->db->bind(':body', $post['body'], null);

        //Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deletePost($postId)
    {
        $this->db->query('DELETE FROM posts WHERE id = :postId');
        $this->db->bind(':postId', $postId, null);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getPostById($id)
    {
        $this->db->query("SELECT * FROM posts WHERE id = :postId");

        $this->db->bind(':postId', $id, null);
        $row = $this->db->single();

        return $row;
    }
}
