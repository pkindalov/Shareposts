<?php require_once APPROOT . '/views/inc/header.php' ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1>Posts of <?php echo $data['userName']->name; ?></h1>
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
        <?php if(isAdmin()) {
         /*   echo "<a href="<?php echo URLROOT; ?>/posts/edit/<?php echo $data['post']->id; ?>" class="btn btn-dark">Edit</a>"; */

            echo "<a href=" .URLROOT. "/posts/edit/" .$post->id . " class='btn btn-dark'>Edit</a>";
            
        } ?>
    </div>

<?php endforeach; ?>

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



<?php require_once APPROOT . '/views/inc/footer.php' ?>