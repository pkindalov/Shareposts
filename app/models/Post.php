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
        $this->db->query("SELECT, 
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
        $this->db->query("SELECT 
        posts.id AS postId, 
        posts.body AS body,
        posts.title AS title,
        posts.approved,
        users.id AS userId,
        users.name AS 'name',
        posts.created_at AS postCreated,
        users.created_at AS userRegistered,
        :currentlyLoggedUser IN (SELECT likes.user_id FROM likes WHERE posts.id = likes.post_id) AS voted,
        -- posts.user_id IN (SELECT likes.user_id FROM likes WHERE likes.user_id = posts.user_id ) AS voted,
        (SELECT COUNT(id) FROM likes WHERE likes.post_id = posts.id ) AS totalVotes
        -- comments.text AS commentText,
        -- comments.created_at AS commentCreatedDate,
        -- comments.user_id AS commentAuthorId,
        -- u2.name AS commentAuthorName
        FROM posts
        LEFT JOIN likes ON likes.post_id = posts.id
        INNER JOIN users ON posts.user_id = users.id 
        
        -- LEFT JOIN users u2 ON u2.id = comments.user_id 
-- 									 LEFT JOIN comments c ON c.user_id = users.id           
        -- GROUP BY likes.post_id, comments.id
        GROUP BY likes.post_id, posts.id
        HAVING posts.approved = 1
        ORDER BY posts.created_at DESC
        LIMIT :limit
        OFFSET :offset
      ");


        //         $this->db->query("SELECT 
        //         posts.id AS postId, 
        //         posts.body AS body,
        //         posts.title AS title,
        //         posts.approved,
        //         users.id AS userId,
        //         users.name AS `name`,
        //         posts.created_at AS postCreated,
        //         users.created_at AS userRegistered,
        //         9 IN (SELECT likes.user_id FROM likes WHERE posts.id = likes.post_id) AS voted,
        //         -- posts.user_id IN (SELECT likes.user_id FROM likes WHERE likes.user_id = posts.user_id ) AS voted,
        //         (SELECT COUNT(id) FROM likes WHERE likes.post_id = posts.id ) AS totalVotes
        //         -- comments.text AS commentText,
        //         -- comments.created_at AS commentCreatedDate,
        //         -- comments.user_id AS commentAuthorId,
        //         -- u2.name AS commentAuthorName
        //         FROM posts
        //         LEFT JOIN likes ON likes.post_id = posts.id
        //         INNER JOIN users ON posts.user_id = users.id 

        //         -- LEFT JOIN users u2 ON u2.id = comments.user_id 
        // -- 									 LEFT JOIN comments c ON c.user_id = users.id           
        //         -- GROUP BY likes.post_id, comments.id
        //         GROUP BY likes.post_id, posts.id
        //         HAVING posts.approved = 1
        //         ORDER BY posts.created_at DESC
        //         LIMIT 5
        //         OFFSET 0
        //         ");


        $this->db->bind(':limit', $pageSize, null);
        $this->db->bind(':offset', $offset, null);
        $this->db->bind(':currentlyLoggedUser', $_SESSION['user_id'], null);
        // $this->db->bind(':userId', $_SESSION['user_id'], null);

        $results = $this->db->execute();
        if ($this->db->rowCount($results) == 0) {
            return [];
        } else {
            $results = $this->db->resultSet();
            return $results;
        }



        // $results = $this->db->resultSet();
        // print_r($results);
        // $total_pages = ceil(count($results) / $pageSize);

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

    public function getPostsForApproving($page, $pageSize)
    {
        if ($page <= 0) {
            $page = 1;
        }

        $offset = ($page - 1) * $pageSize;

        $this->db->query("SELECT posts.id AS postId, posts.title, posts.body, posts.created_at,
                                 users.name,users.email,users.id AS userId
                          FROM posts
                          INNER JOIN users ON users.id = posts.user_id
                          WHERE posts.approved = 0
                          LIMIT :limit
                          OFFSET :offset
        ");

        $this->db->bind(":limit", $pageSize, null);
        $this->db->bind(":offset", $offset, null);

        $results = $this->db->execute();
        if ($this->db->rowCount($results) == 0) {
            return [];
        } else {
            $results = $this->db->resultSet();
            return $results;
        }
    }

    public function approvePostById($postId)
    {
        $this->db->query("UPDATE posts
                          SET approved = 1
                          WHERE posts.id = :postId
        ");

        $this->db->bind(":postId", $postId, null);
        $this->db->execute();
    }

    public function getCountNotApprovedPostsYet()
    {
        $this->db->query("SELECT
                          COUNT(*) AS count FROM posts
                          WHERE posts.approved = 0
        ");

        $result = $this->db->single();
        return $result;
    }
}
