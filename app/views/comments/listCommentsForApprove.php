<?php require APPROOT . '/views/inc/header.php'; ?>
<!-- <pre>
       <?php print_r($data); ?>
    </pre> -->


<?php if (!$data['comments']) : ?>
    <h4>No more comments</h4>

<?php else : ?>

    <?php foreach ($data['comments'] as $comment) : ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-body mb-3">
                    <h4 class="card-title">
                        Post Title: <?php echo $comment->postTitle; ?>
                    </h4>
                    <p class="card-text">
                        Post Text: <?php echo $comment->postText; ?>
                    </p>
                    <p class="card-text">
                        <a href="<?php echo URLROOT; ?>/posts/show/<?php echo $comment->commentPostId; ?>" class="btn btn-dark pull-left mb-2"><i class="fa pr-2">&#xf05a;</i>More...</a>
                    </p>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card card-body mb-3 ml-5">
                    <h4 class="card-title">
                        Comment Text: <?php echo $comment->commentText; ?>
                    </h4>
                    <p class="card-text">
                        Comment Author: <?php echo $comment->commentAuthor; ?>
                    </p>
                    <p class="card-text">
                        Comment Author Email: <?php echo $comment->commentAuthorEmail; ?>
                    </p>
                    <p class="card-text">
                        Comment Created On: <?php echo $comment->commentCreated; ?>
                    </p>
                    <p class="card-text">
                        <a href="<?php echo URLROOT; ?>/comments/approveComment/<?php echo $comment->commentId; ?>" class="btn btn-success pull-left mb-2"><i class="fa pr-2">&#xf05a;</i>Approve</a>
                    </p>
                </div>
            </div>
        </div>
        <!-- <div class="row">
        <div class="col-lg-12">
            <div class="card card-body mb-3 ml-5">
                <h4 class="card-title">
                    Post Title: <?php echo $comment->postTitle; ?>
                </h4>   
                <p class="card-text">
                   Post Text: <?php echo $comment->postText; ?>
                </p>
            </div>
        </div> 
    </div> -->

    <?php endforeach; ?>

    <div class="row">
        <div class="col-md-12">
            <?php if ($data['hasPrevPage']) : ?>
                <a href="<?php echo URLROOT; ?>/comments/commentsForApproving/<?php echo $data['prevPage']; ?>" class="btn btn-primary pull-left">
                    <i class="fa fa-backward"></i> Prev
                </a>
            <?php endif; ?>

            <?php if ($data['hasNextPage']) : ?>
                <a href="<?php echo URLROOT; ?>/comments/commentsForApproving/<?php echo $data['nextPage']; ?>" class="btn btn-primary pull-right">
                    <i class="fa fa-forward"></i> Next
                </a>
            <?php endif; ?>
        </div>
    </div>

<?php endif; ?>

<?php require APPROOT . '/views/inc/footer.php'; ?>