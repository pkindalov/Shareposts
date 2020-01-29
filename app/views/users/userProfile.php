<?php require_once APPROOT . '/views/inc/header.php' ?>
<div class="row mb-3">
    <div class="col-md-6">
        <h1>Name: <?php echo $data['user']->name; ?></h1>
        <h5>Role: <?php echo $data['user']->role; ?></h5>
        <h5>Here from: <?php echo $data['user']->created_at; ?></h5>
        <h5>Email: <?php echo $data['user']->email; ?></h5>
        <h5>Total count of posts: <a href="<?php echo URLROOT; ?>/users/getUserPosts?id=<?php echo $data['user']->id; ?>?page=1"><?php echo $data['postsCount'];?></a></h5>
        <!-- <p>All registered users <strong>but not Admins</strong></p> -->
        <!-- <p><?php echo URLROOT; ?>/users/getUserPosts?id=<?php echo $data['user']->id; ?>&&page=1</p> -->
    </div>
</div>
<hr />

<h1>Last 2 posts:</h1>

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
            echo "<a href=" .URLROOT. "/posts/edit/" .$post->id . " class='btn btn-dark pull-left'>Edit</a>";

            echo "<form class=" . "pull-right action=" .URLROOT. "/posts/delete/" .$post->id ." method=" . "post" . ">" . "<input type=" . "submit " .  "value=" . "Delete " . "class='" . "btn btn-danger'" . "/>" .
        "</form>";
           
        //     echo "<form class=\"pull-right\" action=" .URLROOT. "/posts/delete/" .$post->id. "method=" . "post" . ">" . "<input type=" . "submit" .  "value=" . "Delete" .  "class=\"btn btn-danger\" />
        // </form>";
            
        } ?>
    </div>

<?php endforeach; ?>

<?php require_once APPROOT . '/views/inc/footer.php' ?>