<?php require_once APPROOT . '/views/inc/header.php' ?>
<?php flash('post_message'); ?>
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
<?php foreach ($data['posts'] as $post) : ?>
    <?php if($post->userId == $_SESSION['user_id']) : ?>
        <div class="card card-body mb-3 bg-info">
            
            <?php else : ?>
                <div class="card card-body mb-3">
    
    <?php endif; ?>
    
        <h4 class="card-title">
            <?php echo $post->title; ?>
        </h4>
        <div class="bg-light p-2 mb-3">
            <p>This post has <strong><?php echo $post->totalVotes; ?></strong> likes </p>
            Written By <?php echo $post->name ?> on <?php echo $post->postCreated; ?>
        </div>
        <p class="card-text">
            <?php echo $post->body ?>
        </p>
        <div>
            <a href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->postId; ?>" class="btn btn-dark pull-left mb-2"><i class="fa">&#xf05a;</i>More...</a>


            <?php if ($post->voted && $post->voted ===  $_SESSION['user_id'] && $post->userId != $_SESSION['user_id']) : ?>
               
                <a href="<?php echo URLROOT; ?>/likes/dislikePost/<?php echo $post->postId; ?>" class="btn btn-primary pull-right"><i class="fas">&#xf165;</i>Dislike</a>

            <?php else : ?>

                <?php if($post->userId != $_SESSION['user_id']) : ?>
                <a href="<?php echo URLROOT; ?>/likes/addLikeToPost/<?php echo $post->postId; ?>" class="btn btn-primary pull-right"><i class="fa fa-heart"></i>Like</a>
                <?php endif; ?>
        </div>
    <?php endif; ?>
    </div>
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


<?php require_once APPROOT . '/views/inc/footer.php' ?>