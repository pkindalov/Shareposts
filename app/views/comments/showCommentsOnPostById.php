<?php require_once APPROOT . '/views/inc/header.php' ?>
<div class="row mb-3">
    <div class="col-md-6">
        <h1>Posts</h1>
        <!-- <pre>
            <?php print_r($data); ?>
        </pre> -->
    </div>

</div>

<?php if ($data['content']) : ?>


    <?php if ($data['content']['userId'] == $_SESSION['user_id']) : ?>
        <div class="card card-body mb-3 bg-info">

        <?php else : ?>
            <div class="card card-body mb-3">

            <?php endif; ?>

            <h4 class="card-title">
                <?php echo $data['content']['postTitle']; ?>
            </h4>
            <div class="bg-light p-2 mb-3">
                Written By <?php echo $data['content']['postUserName']; ?> on <?php echo $data['content']['postCreatedOn']; ?>
            </div>
            <p class="card-text">
                <?php echo $data['content']['postContent']; ?>
            </p>



            <div>
                <!-- <a href="<?php echo URLROOT; ?>/posts/show/<?php echo $data['content']['postId'] ?>" class="btn btn-dark pull-left mb-2"><i class="fa pr-2">&#xf05a;</i>More...</a> -->

                <a href="<?php echo URLROOT; ?>/comments/addCommentToPost/<?php echo $data['content']['postId']; ?>" class="btn btn-info pull-left mb-2 ml-2"><i class="fa pr-2">&#xf05a;</i>Add Comment...</a>

                <?php if ($data['content']['voted'] && $data['content']['voted'] > 0 && $data['content']['userId'] != $_SESSION['user_id']) : ?>


                    <a href="<?php echo URLROOT; ?>/likes/dislikePost/<?php echo $data['content']['postId']; ?>" class="btn btn-primary pull-right"><i class="fas pr-2">&#xf165;</i>Dislike</a>
            </div>

        <?php else : ?>

            <?php if ($data['content']['userId'] != $_SESSION['user_id']) : ?>
                <a href="<?php echo URLROOT; ?>/likes/addLikeToPost/<?php echo $data['content']['postId']; ?>" class="btn btn-primary pull-right"><i class="fa fa-heart pr-2"></i><?php echo $data['content']['totalLikes']; ?> Like</a>
            <?php endif; ?>
            </div>

        <?php endif; ?>
        </div>

        <div id="commentBoxPost<?php echo $data['content']['postId']; ?>">

            <!-- <?php echo $data['content']['commentInfo']['commentText'][0]; ?> -->

            <?php for ($c = 0; $c < count($data['content']['commentInfo']['commentText']); $c++) :  ?>

                <!-- <?php echo $data['content']['commentInfo']['commentText'][$c]; ?> -->

                <?php if ($data['content']['commentInfo']['commentAuthor'][$c]== $_SESSION['user_id']) : ?>
                    <div class="card card-body mb-3 bg-info">

                    <?php else : ?>
                        <div class="card card-body mb-3">

                        <?php endif; ?>

                        <div class="card card-body mb-3 ml-5">
                            <div class="bg-light p-2 mb-3">
                                <?php echo $data['content']['commentInfo']['commentText'][$c]; ?>
                            </div>
                            <p class="card-text">
                                Written By <?php echo $data['content']['commentInfo']['commentAuthorName'][$c]; ?> on <?php echo $data['content']['commentInfo']['commentCreated'][$c]; ?>
                            </p>


                        </div>




                    <?php endfor; ?>



                    <!-- <div class="card card-body mb-3 ml-5">
                <div class="bg-light p-2 mb-3">
                    <?php echo $post->commentText; ?>
                </div>
                <p class="card-text">
                    Written By <?php echo $post->commentAuthorName ?> on <?php echo $post->commentCreated; ?>
                </p>


            </div> -->



                    <!-- <?php if (isset($post->comment)) : ?>
                    <p class="text-success">See comments <a href="<?php echo URLROOT; ?>/comments/showCommentOnPost?post=<?php echo $post->postId; ?>&page=1">here</a> on post</p>
                <?php endif; ?> -->
                        </div>






                        <div class="row">
                            <div class="col-md-12">


                                <?php if ($data['hasPrevPage']) : ?>
                                    <a href="<?php echo URLROOT; ?>/comments/showCommentOnPost?post=<?php echo $data['postId']; ?>&page=<?php echo $data['prevPage']; ?>" class="btn btn-primary pull-left">
                                        <i class="fa fa-backward"></i> Prev
                                    </a>
                                <?php endif; ?>

                                <?php if ($data['hasNextPage']) : ?>
                                    <a href="<?php echo URLROOT; ?>/comments/showCommentOnPost?post=<?php echo $data['postId']; ?>&page=<?php echo $data['nextPage']; ?>" class="btn btn-primary pull-right">
                                        <i class="fa fa-forward"></i> Next
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                    <?php else : ?>
                        <p>No comments</p>

                        <?php if ($data['hasPrevPage']) : ?>
                            <a href="<?php echo URLROOT; ?>/comments/showCommentOnPost?post=<?php echo $data['postId']; ?>&page=<?php echo $data['prevPage']; ?>" class="btn btn-primary pull-left">
                                <i class="fa fa-backward"></i> Prev
                            </a>
                        <?php endif; ?>

                    <?php endif; ?>


                    <?php require_once APPROOT . '/views/inc/footer.php' ?>