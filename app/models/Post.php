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
        $this->db->query("SELECT DISTINCT 
                            posts.id AS postId, 
                            posts.body AS body,
                            posts.title AS title,
                            users.id AS userId,
                            users.name AS name,
                            posts.created_at AS postCreated,
                            users.created_at AS userRegistered,
                            :currentlyLoggedUser IN (SELECT likes.user_id FROM likes WHERE posts.id = likes.post_id) AS voted,
                            -- posts.user_id IN (SELECT likes.user_id FROM likes WHERE likes.user_id = posts.user_id ) AS voted,
                            (SELECT COUNT(id) FROM likes WHERE likes.post_id = posts.id ) AS totalVotes
                            FROM posts 
                            LEFT JOIN likes
                            ON likes.post_id = posts.id
                            INNER JOIN users 
                            ON posts.user_id = users.id
                            GROUP BY likes.post_id
                            ORDER BY posts.created_at DESC
                            LIMIT :limit
                            OFFSET :offset
      ");

        $this->db->bind(':limit', $pageSize, null);
        $this->db->bind(':offset', $offset, null);
        $this->db->bind(':currentlyLoggedUser', $_SESSION['user_id'], null);
        // $this->db->bind(':userId', $_SESSION['user_id'], null);

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


    public function getPeopleLikesPost($id)
    {
        $this->db->query("
        SELECT likes.*, users.name FROM likes
        INNER JOIN users ON likes.user_id = users.id
        WHERE likes.post_id = :postId;
        ");

        $this->db->bind(":postId", $id, null);
        $result = $this->db->resultSet();
        return $result;
    }

    public function getAuthorOfPostByPostId($postId)
    {
        $this->db->query("SELECT user_id FROM posts WHERE posts.id = :postId");
        $this->db->bind(":postId", $postId, null);
        $row = $this->db->single();
        return $row;
    }
}
