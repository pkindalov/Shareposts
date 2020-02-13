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
    <?php foreach($data['likers'] as $liker) : ?>
        <a class="btn btn-success mt-2" href="<?php echo URLROOT; ?>/users/showUser/<?php echo $liker->user_id; ?>"><?php echo $liker->name; ?></a>
        <?php endforeach; ?>    
</div>


<?php require APPROOT . '/views/inc/footer.php'; ?>