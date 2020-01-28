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

<?php require_once APPROOT . '/views/inc/footer.php' ?>