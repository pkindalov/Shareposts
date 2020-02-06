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
}
