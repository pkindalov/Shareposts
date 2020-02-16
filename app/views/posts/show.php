<?php require APPROOT . '/views/inc/header.php'; ?>
<!-- <pre>
   <?php print_r($data); ?>
</pre> -->
<a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward pr-2"></i>Back</a>
<br />
<h1><?php echo $data['post']->title ?></h1>
<div class="bg-secondary text-white p-2 mb-3">
    Written by <?php echo $data['user']->name; ?> on <?php echo $data['post']->created_at; ?>
</div>
<p><?php echo $data['post']->body; ?></p>
<?php if ($data['post']->user_id == $_SESSION['user_id'] || $_SESSION['role'] == 'admin') : ?>
    <hr />
    <a href="<?php echo URLROOT; ?>/posts/edit/<?php echo $data['post']->postId; ?>" class="btn btn-dark">Edit</a>
    <form class="pull-right" action="<?php echo URLROOT; ?>/posts/delete/<?php echo $data['post']->postId; ?>" method="post">
        <input type="submit" value="Delete" class="btn btn-danger" />
    </form>
<?php endif; ?>

<div class="mt-3">
    <h5>People liked this post</h5>
    <?php foreach ($data['likers'] as $liker) : ?>
        <a class="btn btn-success mt-2" href="<?php echo URLROOT; ?>/users/showUser/<?php echo $liker->user_id; ?>"><?php echo $liker->name; ?></a>
    <?php endforeach; ?>
</div>



<?php if (isset($data['comments']) && count($data['comments']) > 0) : ?>


   <div class="mt-4">
    <?php foreach ($data['comments'] as $comment) : ?>

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
        </div>
            <?php if (isset($data['comments'])) : ?>
                <p class="text-success">See comments <a href="<?php echo URLROOT; ?>/comments/showCommentOnPost?post=<?php echo $data['post']->id; ?>&page=1">here</a> on post</p>
            <?php endif; ?>
            

        <?php endif; ?>









<?php require APPROOT . '/views/inc/footer.php'; ?>