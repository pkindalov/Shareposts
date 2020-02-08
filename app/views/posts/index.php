<?php require_once APPROOT . '/views/inc/header.php' ?>
<?php flash('post_message'); ?>
<?php flash('comment_message'); ?>
<div class="row mb-3">
    <div class="col-md-6">
        <h1>Posts</h1>
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

<?php if(!$data['posts']) : ?>

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
                <!-- <?php if(isset($post->comment)) : ?>
                    <p class="text-success">There are <a href="<?php echo URLROOT;?>/posts/showCommentOnPost/<?php echo $post->postId; ?>">comments</a> on post</p>
                <?php endif; ?> -->
                Written By <?php echo $post->name ?> on <?php echo $post->postCreated; ?>
            </div>
            <p class="card-text">
                <?php echo $post->body ?>
            </p>



            <div>
                <a href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->postId; ?>" class="btn btn-dark pull-left mb-2"><i class="fa pr-2">&#xf05a;</i>More...</a>

                <a href="<?php echo URLROOT; ?>/comments/addCommentToPost/<?php echo $post->postId; ?>" class="btn btn-info pull-left mb-2 ml-2"><i class="fa pr-2">&#xf05a;</i>Add Comment...</a>

                <?php if(isset($post->comment)) : ?>
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
        </div>

        <?php if (isset($post->comment) && count($post->comment) > 0) : ?>

           

            <div class="d-none" id="commentBoxPost<?php echo $post->postId; ?>">
            <?php foreach ($post->comment as $comment) : ?>

                    <div class="card card-body mb-3 ml-5">
                        <div class="bg-light p-2 mb-3">
                            <?php echo $comment->text ?>
                        </div>
                        <p class="card-text">
                            Written By <?php echo $comment->name ?> on <?php echo $comment->created_at; ?>
                        </p>
    
                        
                    </div>
                    
                    
                    <?php endforeach; ?>
                     <?php if(isset($post->comment)) : ?>
                    <p class="text-success">See comments <a href="<?php echo URLROOT;?>/comments/showCommentOnPost?post=<?php echo $post->postId; ?>&page=1">here</a> on post</p>
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