<?php require_once APPROOT . '/views/inc/header.php' ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1>Posts</h1>
        <!-- <pre>
            <?php print_r($data); ?>
        </pre> -->
    </div>
</div>

<?php if(!$data['posts']) : ?>

    <h3>Sorry, no posts found</h3>

<?php else : ?>    

    <p>Total count of new posts for reviewing: <?php echo count($data['posts']); ?></p>

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
                <!-- <p>This post has <strong><?php echo $post->totalVotes; ?></strong> likes </p> -->
                <!-- <?php if(isset($post->comment)) : ?>
                    <p class="text-success">There are <a href="<?php echo URLROOT;?>/posts/showCommentOnPost/<?php echo $post->postId; ?>">comments</a> on post</p>
                <?php endif; ?> -->
                Written By <?php echo $post->name ?> on <?php echo $post->created_at; ?>
            </div>
            <p class="card-text">
                <?php echo $post->body ?>
            </p>



            <div>
                <a href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->postId; ?>" class="btn btn-dark pull-left mb-2"><i class="fa pr-2">&#xf05a;</i>More...</a>

                <a href="<?php echo URLROOT; ?>/posts/approvePost/<?php echo $post->postId; ?>" class="btn btn-success pull-left mb-2 ml-2"><i class="fa pr-2">&#xf05a;</i>Approve</a>

                <a href="<?php echo URLROOT; ?>/posts/declinePost/<?php echo $post->postId; ?>" class="btn btn-danger pull-left mb-2 ml-2"><i class="fa pr-2">&#xf05a;</i>Decline</a>

        </div>


    <?php endforeach; ?>


    <div class="row">
        <div class="col-md-12">


            <?php if ($data['hasPrevPage']) : ?>
                <a href="<?php echo URLROOT; ?>/posts/postsForApproving/<?php echo $data['prevPage']; ?>" class="btn btn-primary pull-left">
                    <i class="fa fa-backward"></i> Prev
                </a>
            <?php endif; ?>

            <?php if ($data['hasNextPage']) : ?>
                <a href="<?php echo URLROOT; ?>/posts/postsForApproving/<?php echo $data['nextPage']; ?>" class="btn btn-primary pull-right">
                    <i class="fa fa-forward"></i> Next
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php endif; ?>

    <?php require_once APPROOT . '/views/inc/footer.php' ?>