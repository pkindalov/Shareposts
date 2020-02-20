<?php require_once APPROOT . '/views/inc/header.php' ?>

<!-- <pre>
    <?php print_r($data['userId']); ?>
</pre> -->

<div class="row mb-3">
    <div class="col-md-6">
        <h1>Not Approved Posts of <?php echo $data['userName']->name; ?></h1>
        <p>If your post is here, then you must correct your post</p>
    </div>

</div>


<?php
if (count($data['posts']) == 0) {
    echo '<div class="card card-body mb-3">';
    echo '  <h4 class="card-title">';
    echo "No posts";
    echo "  </h4>";
    echo "</div>";
}
?>



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
        <!-- <?php if (isAdmin()) {
                    /*   echo "<a href="<?php echo URLROOT; ?>/posts/edit/<?php echo $data['post']->id; ?>" class="btn btn-dark">Edit</a>"; */
                    //     echo "<div>";
                    //     echo "<a href=" .URLROOT. "/posts/edit/" .$post->id . " class='btn btn-dark pull-left'>Edit</a>";

                    //     echo "<form class=" . "pull-right action=" .URLROOT. "/posts/delete/" .$post->id ." method=" . "post" . ">" . "<input type=" . "submit " .  "value=" . "Delete " . "class='" . "btn btn-danger'" . "/>" .
                    // "</form>";
                    //     echo "</div>";

                    //     echo "<form class=\"pull-right\" action=" .URLROOT. "/posts/delete/" .$post->id. "method=" . "post" . ">" . "<input type=" . "submit" .  "value=" . "Delete" .  "class=\"btn btn-danger\" />
                    // </form>";

                } ?>
    </div> -->


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

        <?php if ($_SESSION['user_id'] == $data['userId']) :  ?>
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

<div class="mt-5">
    <?php if ($data['hasPrevPage']) : ?>
        <a href="<?php echo URLROOT; ?>/users/getUserPosts?id=<?php echo $data['userId']; ?>?page=<?php echo $data['prevPage']; ?>" class="btn btn-primary pull-left">
            <i class="fa fa-backward"></i> Prev
        </a>
    <?php endif; ?>

    <?php if ($data['hasNextPage']) : ?>
        <a href="<?php echo URLROOT; ?>/users/getUserPosts?id=<?php echo $data['userId']; ?>?page=<?php echo $data['nextPage']; ?>" class="btn btn-primary pull-right">
            <i class="fa fa-forward"></i> Next
        </a>
    <?php endif; ?>

</div>



<?php require_once APPROOT . '/views/inc/footer.php' ?>