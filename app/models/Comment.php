<?php
class Comment
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function addComment($comment)
    {
        $this->db->query('INSERT INTO comments (text, post_id, user_id) 
                              VALUES (:text, :post_id, :user_id)
            ');

        $this->db->bind(':text', $comment['text'], null);
        $this->db->bind(':post_id', $comment['post_id'], null);
        $this->db->bind(':user_id', $comment['user_id'], null);

        //Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getCommentsByPostsIds($postsIds)
    {
        $numberList = join(',', $postsIds);
        $this->db->query("SELECT comments.*, users.name, users.email, users.role FROM comments 
                          INNER JOIN users ON users.id = comments.user_id
                          WHERE comments.post_id IN (:numberList) AND comments.approved = 1
                          LIMIT 5
        
        ");
        $this->db->bind(":numberList", $numberList, null);
        $result = $this->db->execute();

        if ($this->db->rowCount($result) == 0) {
            return [];
        } else {
            $result = $this->db->resultSet();
            return $result;
        }
    }

    public function getCommentsToPostPage($postId, $page, $pageSize)
    {
        $page  = $page == 0 ? 1 : $page;
        $offset = ($page - 1) * $pageSize;


        $this->db->query("SELECT DISTINCT posts.title AS postTitle,
                                 posts.body AS postContent,
                                 posts.created_at AS postCreatedOn,
                                 posts.user_id AS userId,
                                 posts.id AS postId,
                                 comments.text AS commentText, 
                                 comments.created_At AS commentCreated,
                                 comments.user_id AS commentAuthor,
                                 users.name AS postUserName, 
                                 users.email AS postUserEmail,
                                 (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) AS totalLikes,
                                --  GROUP_CONCAT(commentUsers.name,
                                --               commentUsers.email) AS commentData,
                                 commentUsers.name AS commentAuthorName,
                                 commentUsers.email AS commentAuthorEmail,
                                 :currentlyLoggedUser IN (SELECT likes.user_id FROM likes WHERE posts.id = likes.post_id) AS voted
                          FROM posts
                          INNER JOIN comments ON comments.post_id = posts.id
                          INNER JOIN users ON posts.user_id = users.id
                          INNER JOIN users commentUsers ON comments.user_id = commentUsers.id
                          WHERE posts.id = :postId AND comments.approved = 1
                          LIMIT :limit
                          OFFSET :offset  
        ");

        $this->db->bind(":postId", $postId, null);
        $this->db->bind(":limit", $pageSize, null);
        $this->db->bind(":offset", $offset, null);
        $this->db->bind(":currentlyLoggedUser", $_SESSION['user_id'], null);

        $result = $this->db->resultSet();

        return $result;
    }

    public function getCommentsForApprove($page, $pageSize)
    {
        $page  = $page == 0 ? 1 : $page;
        $offset = ($page - 1) * $pageSize;

        $this->db->query("SELECT comments.id AS commentId,
          comments.text AS commentText, 
          comments.post_id AS commentPostId, 
          comments.user_id AS commentAuthorId,
          users.name AS commentAuthor,
          users.email AS commentAuthorEmail,
          comments.created_at AS commentCreated,
          posts.title AS postTitle,
          posts.body AS postText
          FROM	comments
          INNER JOIN posts ON posts.id = comments.post_id
          INNER JOIN users ON users.id = comments.user_id
          WHERE comments.approved = 0
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

    public function getCountNotApprovedCommentsYet()
    {
        $this->db->query("SELECT
                          COUNT(*) AS count FROM comments
                          WHERE comments.approved = 0
        ");

        $result = $this->db->single();
        return $result;
    }

    public function approveCommentById($commentId)
    {
        $this->db->query("UPDATE comments
                          SET approved = 1
                          WHERE comments.id = :commentId
        ");

        $this->db->bind(":commentId", $commentId, null);
        $this->db->execute();
    }

    public function getCommentForEditById($commentId)
    {
        $this->db->query("SELECT comments.id, comments.text, comments.user_id
                          FROM comments
                          WHERE comments.id = :commentId
        ");

        $this->db->bind(":commentId", $commentId, null);

        $result = $this->db->single();
        return $result;
    }

    public function editComment($comment)
    {
        // print_r($comment);
        $this->db->query('UPDATE comments SET text = :text WHERE comments.id = :commentId');

        $this->db->bind(':text', $comment['text'], null);
        $this->db->bind(':commentId', $comment['comment_id'], null);

        //Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
