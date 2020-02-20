<?php require_once APPROOT . '/views/inc/header.php' ?>
<!-- <pre>
        <?php print_r($data['posts']); ?>
    </pre> -->

<div class="row mb-3">
    <div class="col-md-6">
        <h1>Name: <?php echo $data['user']->name; ?></h1>
        <hr />
        <h5>Role: <?php echo $data['user']->role; ?></h5>
        <h5>Here from: <?php echo $data['user']->created_at; ?></h5>
        <h5>Email: <?php echo $data['user']->email; ?></h5>
        <h5>Total count of posts: <a href="<?php echo URLROOT; ?>/users/getUserPosts?id=<?php echo $data['user']->id; ?>?page=1"><?php echo $data['postsCount']; ?></a></h5>

        <h5>Liked posts: <a href="<?php echo URLROOT; ?>/users/getLikedUserPosts?id=<?php echo $data['user']->id; ?>?page=1"><?php echo $data['userLikedPostsCount']; ?></a></h5>


        <?php if($_SESSION['role'] != 'admin') : ?>
            
               <h5>Not Approved posts<a href="<?php echo URLROOT; ?>/users/getUserNotApprovedPosts?id=<?php echo $_SESSION['user_id']; ?>?page=1"><?php echo $data['countNotApprovedPosts'] ?></a></h5>
               <h5>Approved posts <a href="<?php echo URLROOT; ?>/users/getUserPosts?id=<?php echo $_SESSION['user_id']; ?>?page=1"><?php echo $data['countApprovedPosts'] ?></a></h5>
            
        <?php endif; ?>


        <!-- <p>All registered users <strong>but not Admins</strong></p> -->
        <!-- <p><?php echo URLROOT; ?>/users/getUserPosts?id=<?php echo $data['user']->id; ?>&&page=1</p> -->
    </div>
</div>
<hr />

<?php if (count($data['posts']) > 0) : ?>
    <h1>Latest posts:</h1>

    <?php foreach ($data['posts'] as $post) : ?>
        <div class="card card-body mb-3">
            <h4 class="card-title">
                <?php echo $post->title ?>
            </h4>
            <hr />
            <h5>Title</h5>
            <p><?php echo $post->body; ?></p>
            <hr />
            <h6>Posted on:</h6>
            <p><?php echo $post->created_at; ?></p>
            
            <?php if (isAdmin()) : ?>
                <div>
                    <a class="btn btn-dark pull-left" href="<?php echo URLROOT; ?>/posts/edit/<?php echo $post->id; ?>">Edit</a>
                  

                    <button type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target="#post<?php echo $post->id; ?>">
                        Delete Post
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="post<?php echo $post->id; ?>" tabindex="-1" role="dialog" aria-labelledby="postLabel<?php echo $post->id; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="postLabel<?php echo $post->id; ?>">Are you sure to delete <?php echo $post->title; ?>?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <?php echo $post->body; ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                    <a class="btn btn-danger" href="<?php echo URLROOT ?>/posts/delete/<?php echo $post->id; ?>">Delete</a>
                                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                </div>
                            </div>
                        </div>
                    </div>



                </div>

            <?php endif; ?>



        </div>

    <?php endforeach; ?>
<?php endif; ?>


<?php if ($data['comments'] && count($data['comments']) > 0) : ?>
    <h1 class="mt-5">Latest comments:</h1>

    <?php foreach ($data['comments'] as $comment) : ?>
        <div class="card card-body mb-3">
            <h4 class="card-title">Post Title:
                <a href="<?php echo URLROOT; ?>/posts/show/<?php echo $comment->postId; ?>"><?php echo $comment->postTitle; ?></a>
            </h4>
            <?php if (isAdmin()) : ?>
                <div>
                    <a class="btn btn-dark pull-left" href="<?php echo URLROOT; ?>/posts/edit/<?php echo $comment->postId; ?>">Edit Post</a>


                    <button type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target="#post<?php echo $comment->postId; ?>">
                        Delete Post sfsd
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="post<?php echo $comment->postId; ?>" tabindex="-1" role="dialog" aria-labelledby="postLabel<?php echo $comment->postId; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="postLabel<?php echo $comment->postId; ?>">Are you sure to delete <?php echo $comment->postTitle; ?>?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <?php echo $comment->postTitle; ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                    <form class="pull-right" action="<?php echo URLROOT; ?>/posts/delete/<?php echo $comment->postId; ?>" method="post">
                                        <input type="submit" value="Delete Post" class="btn btn-danger" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endif; ?>
            <hr />
            <div class="ml-5">
                <h5>Comment</h5>
                <p><?php echo $comment->comment; ?></p>
                <h6>Posted on:</h6>
                <p><?php echo $comment->created_at; ?></p>
                <h6>Author:</h6>
                <p><?php echo $comment->name; ?></p>
            </div>
            <hr />


            <?php if (isAdmin()) : ?>
                <div>
                    <a class="btn btn-dark pull-left" href="<?php echo URLROOT; ?>/comments/editComment/<?php echo $comment->commentId; ?>">Edit Comment</a>
                    <!-- <form class="pull-right" action="<?php echo URLROOT; ?>/comments/deleteComment/<?php echo $comment->commentId; ?>" method="post">
                        <input type="submit" value="Delete Comment" class="btn btn-danger" />
                    </form> -->

                    <button type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target="#comment<?php echo $comment->commentId; ?>">
                        Delete Comment
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="comment<?php echo $comment->commentId; ?>" tabindex="-1" role="dialog" aria-labelledby="commentLabel<?php echo $comment->commentId; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="commentLabel<?php echo $comment->commentId; ?>">Are you sure to delete <?php echo $comment->comment; ?>?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <?php echo $comment->comment; ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                    <a class="btn btn-danger" href="<?php echo URLROOT ?>/comments/deleteComment/<?php echo $comment->commentId; ?>">Delete</a>
                                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                </div>
                            </div>
                        </div>
                    </div>



                </div>

            <?php endif; ?>

            <!-- <?php if (isAdmin()) {
                        /*   echo "<a href="<?php echo URLROOT; ?>/posts/edit/<?php echo $data['post']->id; ?>" class="btn btn-dark">Edit</a>"; */
                        echo "<div>";
                        echo "<a href=" . URLROOT . "/posts/edit/" . $comment->postId . " class='btn btn-dark pull-left'>Edit</a>";

                        echo "<form class=" . "pull-right action=" . URLROOT . "/posts/delete/" . $comment->postId . " method=" . "post" . ">" . "<input type=" . "submit " .  "value=" . "Delete " . "class='" . "btn btn-danger'" . "/>" .
                            "</form>";
                        echo "</div>";

                        //     echo "<form class=\"pull-right\" action=" .URLROOT. "/posts/delete/" .$post->id. "method=" . "post" . ">" . "<input type=" . "submit" .  "value=" . "Delete" .  "class=\"btn btn-danger\" />
                        // </form>";

                    } ?> -->
        </div>

    <?php endforeach; ?>
<?php endif; ?>

<?php require_once APPROOT . '/views/inc/footer.php' ?>