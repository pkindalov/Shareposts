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

    public function getCommentsByPostsIds($postsIds){
        $numberList = join(',', $postsIds);
        $this->db->query("SELECT comments.*, users.name, users.email, users.role FROM comments 
                          INNER JOIN users ON users.id = comments.user_id
                          WHERE comments.post_id IN ($numberList)
                          LIMIT 5
        
        ");
        $comments = $this->db->resultSet();

        return $comments;
    }

    public function getCommentsToPostPage($postId, $page, $pageSize){
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
                          WHERE posts.id = :postId
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
}
