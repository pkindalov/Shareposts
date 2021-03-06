<?php require_once APPROOT . '/views/inc/header.php' ?>
<?php flash('post_message'); ?>
<?php flash('comment_message'); ?>
<div class="row mb-3">
    <div class="col-md-6">
        <h1>Posts</h1>
        <?php if($_SESSION['role'] != 'admin') : ?>
            <p>
               Not Approved posts <a href="<?php echo URLROOT; ?>/users/getUserNotApprovedPosts?id=<?php echo $_SESSION['user_id']; ?>?page=1"><?php echo $data['countNotApprovedPosts'] ?></a>
               Approved posts <a href="<?php echo URLROOT; ?>/users/getUserPosts?id=<?php echo $_SESSION['user_id']; ?>?page=1"><?php echo $data['countApprovedPosts'] ?></a>
            </p>
        <?php endif; ?>
        <!-- <pre>
            <?php print_r($data); ?>
        </pre> -->
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/posts/add" class="btn btn-primary pull-right">
            <i class="fa fa-pencil"></i> Add post
        </a>
    </div>
</div>

<?php if (!$data['posts']) : ?>

    <h3>Sorry, no posts found</h3>

<?php else : ?>

    <?php foreach ($data['posts'] as $post) : ?>
        <?php if ($post->userId == $_SESSION['user_id']) : ?>
            <div class="card card-body mb-3 bg-info">

            <?php else : ?>
                <div class="card card-body mb-3">

                <?php endif; ?>

                <h4 class="card-title">
                    <?php echo $post->title; ?>
                </h4>
                <div class="bg-light p-2 mb-3">
                    <p>This post has <strong><?php echo $post->totalVotes; ?></strong> likes </p>
                    <!-- <?php if (isset($post->comment)) : ?>
                    <p class="text-success">There are <a href="<?php echo URLROOT; ?>/posts/showCommentOnPost/<?php echo $post->postId; ?>">comments</a> on post</p>
                <?php endif; ?> -->
                    Written By <?php echo $post->name ?> on <?php echo $post->postCreated; ?>
                </div>
                <p class="card-text">
                    <?php echo $post->body ?>
                </p>

                <?php if ($post->userId == $_SESSION['user_id'] || $_SESSION['role'] == 'admin') : ?>
                    <hr />
                    <!-- <div class="mt-3 mb-3">
                        <a href="<?php echo URLROOT; ?>/posts/edit/<?php echo $post->postId; ?>" class="btn btn-dark">Edit</a>
                        <form class="pull-right" action="<?php echo URLROOT; ?>/posts/delete/<?php echo $posr->postId; ?>" method="post">
                            <input type="submit" value="Delete" class="btn btn-danger" />
                        </form>
                    </div> -->

                    <div class="mt-3 mb-3">
                        <a href="<?php echo URLROOT; ?>/posts/edit/<?php echo $post->postId; ?>" class="btn btn-dark">Edit</a>

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#post<?php echo $post->postId; ?>">
                            Delete
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="post<?php echo $post->postId; ?>" tabindex="-1" role="dialog" aria-labelledby="postLabel<?php echo $post->postId; ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="postLabel<?php echo $post->postId; ?>">Are you sure to delete <?php echo $post->title; ?>?</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo $post->body; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                        <form class="pull-right" action="<?php echo URLROOT; ?>/posts/delete/<?php echo $posr->postId; ?>" method="post">
                                            <input type="submit" value="Delete" class="btn btn-danger" />
                                        </form>
                                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>




                <?php endif; ?>


                <div>
                    <a href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->postId; ?>" class="btn btn-dark pull-left mb-2"><i class="fa pr-2">&#xf05a;</i>More...</a>

                    <a href="<?php echo URLROOT; ?>/comments/addCommentToPost/<?php echo $post->postId; ?>" class="btn btn-info pull-left mb-2 ml-2"><i class="fa pr-2">&#xf05a;</i>Add Comment...</a>

                    <?php if (isset($post->comment)) : ?>
                        <a id="showHideCommentsBtn" class="btn btn-warning ml-2" href="#" onclick="showHideCommentsOnPost(<?php echo $post->postId; ?>);">Show Comments</a>
                    <? endif; ?>



                    <?php if ($post->voted && $post->voted > 0 && $post->userId != $_SESSION['user_id']) : ?>


                        <a href="<?php echo URLROOT; ?>/likes/dislikePost/<?php echo $post->postId; ?>" class="btn btn-primary pull-right"><i class="fas pr-2">&#xf165;</i>Dislike</a>
                </div>



            <?php else : ?>

                <?php if ($post->userId != $_SESSION['user_id']) : ?>
                    <a href="<?php echo URLROOT; ?>/likes/addLikeToPost/<?php echo $post->postId; ?>" class="btn btn-primary pull-right"><i class="fa fa-heart pr-2"></i>Like</a>
                <?php endif; ?>
                </div>

            <?php endif; ?>

            <!-- <div>
                Post Rated:
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
            </div> -->


            </div>

                   

            <?php if (isset($post->comment) && count($post->comment) > 0) : ?>



                <div class="d-none" id="commentBoxPost<?php echo $post->postId; ?>">
                    <?php foreach ($post->comment as $comment) : ?>

                        <?php if ($comment->user_id == $_SESSION['user_id']) : ?>
                            <div class="card card-body mb-3 ml-5 bg-info">
                            <?php else : ?>
                                <div class="card card-body mb-3 ml-5">
                                <?php endif; ?>

                                <div class="bg-light p-2 mb-3">
                                    <?php echo $comment->text ?>
                                </div>
                                <p class="card-text">
                                    Written By <?php echo $comment->name ?> on <?php echo $comment->created_at; ?>
                                </p>

                                <?php if ($_SESSION['user_id'] == $comment->user_id || $_SESSION['role'] == 'admin') : ?>
                                    <div>
                                        <a class="btn btn-info" href="<?php echo URLROOT ?>/comments/editComment/<?php echo $comment->id; ?>">Edit</a>

                                        <!-- <a class="btn btn-danger" href="<?php echo URLROOT ?>/comments/deleteComment/<?php echo $comment->id; ?>">Delete</a> -->


                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#comment<?php echo $comment->id; ?>">
                                            Delete
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="comment<?php echo $comment->id; ?>" tabindex="-1" role="dialog" aria-labelledby="commentLabel<?php echo $comment->id; ?>" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="commentLabel<?php echo $comment->id; ?>">Are you sure to delete <?php echo $comment->text; ?>?</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php echo $comment->text; ?>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                                        <a class="btn btn-danger" href="<?php echo URLROOT ?>/comments/deleteComment/<?php echo $comment->id; ?>">Delete</a>
                                                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>







                                    </div>
                                <?php endif; ?>

                                </div>


                            <?php endforeach; ?>
                            <?php if (isset($post->comment)) : ?>
                                <p class="text-success">See comments <a href="<?php echo URLROOT; ?>/comments/showCommentOnPost?post=<?php echo $post->postId; ?>&page=1">here</a> on post</p>
                            <?php endif; ?>
                            </div>

                        <?php endif; ?>

                    <?php endforeach; ?>


                    <div class="row">
                        <div class="col-md-12">
                            <?php if ($data['hasPrevPage']) : ?>
                                <a href="<?php echo URLROOT; ?>/posts/getPage/<?php echo $data['prevPage']; ?>" class="btn btn-primary pull-left">
                                    <i class="fa fa-backward"></i> Prev
                                </a>
                            <?php endif; ?>

                            <?php if ($data['hasNextPage']) : ?>
                                <a href="<?php echo URLROOT; ?>/posts/getPage/<?php echo $data['nextPage']; ?>" class="btn btn-primary pull-right">
                                    <i class="fa fa-forward"></i> Next
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endif; ?>

                <?php require_once APPROOT . '/views/inc/footer.php' ?>