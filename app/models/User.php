<?php
class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    //Registering User
    public function register($data)
    {
        $this->db->query("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");

        //Bind values
        $this->db->bind(":name", $data['name'], null);
        $this->db->bind(":email", $data['email'], null);
        $this->db->bind(":password", $data['password'], null);

        //Execute query
        if ($this->db->execute()) {
            return true;
        }

        return false;
    }


    //Login User
    public function login($email, $password)
    {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email, null);

        $row = $this->db->single();
        $hashed_password = $row->password;

        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }

    //Find user by email
    public function findUserByEmail($email)
    {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email, null);

        $row = $this->db->single();

        //Check row
        if ($this->db->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function getUserById($userId)
    {
        $this->db->query("SELECT users.id, users.name, users.role, users.created_at, users.email FROM users WHERE id = :userId");
        $this->db->bind(':userId', $userId, null);
        $row = $this->db->single();
        return $row;
    }

    public function getUsersPaginated($page, $pageSize)
    {
        if ($page <= 0) {
            $page = 1;
        }

        // $allRecords = $this->getPosts();
        $offset = ($page - 1) * $pageSize;

        // echo $offset;
        $this->db->query("SELECT users.id, users.name, users.email, users.created_at, users.role
                                FROM users 
                                WHERE users.role = 'user'
                                LIMIT :limit
                                OFFSET :offset
          ");

        $this->db->bind(':limit', $pageSize, null);
        $this->db->bind(':offset', $offset, null);
        $users = $this->db->resultSet();
        return $users;
    }

    public function getUserPostsCount($userId)
    {
        $this->db->query("SELECT * FROM posts WHERE posts.user_id = :userId");
        $this->db->bind(":userId", $userId, null);
        $posts = $this->db->resultSet();
        $postsCount = $this->db->rowCount();
        return $postsCount;
    }

    public function getUsersPostWithPag($userId, $page, $pageSize)
    {
        $page  = $page == 0 ? 1 : $page;
        $offset = ($page - 1) * $pageSize;
        $this->db->query("
            SELECT posts.*, users.name 
            FROM posts 
            INNER JOIN users ON users.id = posts.user_id 
            WHERE posts.user_id = :userId 
            LIMIT :limit 
            OFFSET :offset
        ");
        $this->db->bind(":userId", $userId, null);
        $this->db->bind(":limit", $pageSize, null);
        $this->db->bind(":offset", $offset, null);
        $result = $this->db->resultSet();
        return $result;
    }

    public function getUsersNotApprovedPostWithPag($userId, $page, $pageSize)
    {
        $page  = $page == 0 ? 1 : $page;
        $offset = ($page - 1) * $pageSize;
        $this->db->query("
            SELECT posts.*, users.name 
            FROM posts 
            INNER JOIN users ON users.id = posts.user_id 
            WHERE posts.user_id = :userId AND posts.approved = 0
            LIMIT :limit 
            OFFSET :offset
        ");
        $this->db->bind(":userId", $userId, null);
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

    public function getUserLikedPostWithPag($userId, $page, $pageSize)
    {
        $page  = $page == 0 ? 1 : $page;
        $offset = ($page - 1) * $pageSize;
        $this->db->query("
        SELECT * FROM likes
        INNER JOIN posts ON likes.post_id = posts.id
        WHERE likes.user_id = :userId
        LIMIT :limit 
        OFFSET :offset
        ");
        $this->db->bind(":userId", $userId, null);
        $this->db->bind(":limit", $pageSize, null);
        $this->db->bind(":offset", $offset, null);
        $result = $this->db->resultSet();
        return $result;
    }

    public function getLastPosts($userId, $count)
    {
        $this->db->query("SELECT * FROM posts WHERE posts.user_id = :userId  ORDER BY posts.created_at DESC LIMIT :count");
        $this->db->bind(":userId", $userId, null);
        $this->db->bind(":count", $count, null);
        $result = $this->db->resultSet();
        return $result;
    }
}
