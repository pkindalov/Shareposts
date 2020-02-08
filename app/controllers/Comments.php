<?php
    class Comments extends Controller{
        public function __construct()
        {
            $this->commentModel = $this->model('Comment');
        }

        public function addCommentToPost($postId){

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Sanitize post
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $postId = htmlspecialchars($postId);
    
                $data = [
                    'text' => trim($_POST['text']),
                    'user_id' => $_SESSION['user_id'],
                    'post_id' => $postId,
                    'text_err' => '',
                ];
    
                //Validate data
                if (empty($_POST['text'])) {
                    $data['text_err'] = 'Comment cannot be empty';
                }
    
                //Make sure no errors
                if (empty($data['text_err'])) {
                    //Validated
                    if ($this->commentModel->addComment($data)) {
                        flash('comment_message', 'Comment added');
                        redirect('posts');
                    } else {
                        die('Something wrong with adding comment');
                    }
                } else {
                    //Redirect to same view but with errors
                    $this->view('comments/add', $data);
                }
            } else {
    
                $data = [
                    'text' => '',
                    'post_id' => $postId
                ];
                $this->view('comments/add', $data);
            }

            // $data = [
            //     'postId' => $postId
            // ];

            // $this->view('comments/add', $data);
        }

        public function showCommentOnPost($url){
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $queryStr = explode('&', $url);
            $postId = explode('=', $queryStr[1])[1];
            $page = explode('=', $queryStr[2])[1];
            $pageSize = 10;
            
            if(!isset($page)){
                $page = 1;
            }



            $content = $this->commentModel->getCommentsToPostPage($postId, (int)$page, $pageSize);
            $postAndComments = [];

            // print_r($content);

            if(count($content) > 0){

                $postAndComments['postTitle'] = $content[0]->postTitle;
                $postAndComments['postContent'] = $content[0]->postContent;
                $postAndComments['postCreatedOn'] = $content[0]->postCreatedOn;
                $postAndComments['postUserName'] = $content[0]->postUserName;
                $postAndComments['postUserEmail'] = $content[0]->postUserEmail;
                $postAndComments['voted'] = $content[0]->voted;
                $postAndComments['userId'] = $content[0]->userId;
                $postAndComments['postId'] = $content[0]->postId;
                $postAndComments['totalLikes'] = $content[0]->totalLikes;
    
                
                foreach($content as $cont){
                    $postAndComments['commentInfo']['commentText'][] = $cont->commentText;
                    $postAndComments['commentInfo']['commentCreated'][] = $cont->commentCreated;
                    $postAndComments['commentInfo']['commentAuthorName'][] = $cont->commentAuthorName;
                    $postAndComments['commentInfo']['commentAuthorEmail'][] = $cont->commentAuthorEmail;
                    $postAndComments['commentInfo']['commentAuthor'][] = $cont->commentAuthor;
                }
            }
            




            // print_r($postAndComments);

            $data = [
                'content' => $postAndComments,
                'page' => $page,
                'hasNextPage' => count($content) > 0,
                'hasPrevPage' => $page > 1,
                'nextPage' => $page + 1,
                'prevPage' => $page - 1,
                'postId' => $postId
            ];

           
            $this->view('comments/showCommentsOnPostById', $data);

        }
    }