<?php require_once APPROOT . '/views/inc/header.php' ?>
<div class="row mb-3">
    <div class="col-md-6">
        <h1>Users</h1>
        <p>All registered users <strong>but not Admins</strong></p>
    </div>
</div>
<?php foreach ($data['users'] as $user) : ?>
    <div class="card card-body mb-3">
        <h4 class="card-title">
          <a href="<?php echo URLROOT ?>/users/userProfile/<?php echo $user->id ?>"><?php echo $user->name; ?></a>
        </h4>
       
    </div>
<?php endforeach; ?>

<?php if ($data['hasPrevPage']) : ?>
    <a href="<?php echo URLROOT; ?>/users/listUsers/<?php echo $data['prevPage']; ?>" class="btn btn-primary pull-left">
        <i class="fa fa-backward"></i> Prev
    </a>
<?php endif; ?>

<?php if ($data['hasNextPage']) : ?>
    <a href="<?php echo URLROOT; ?>/users/listUsers/<?php echo $data['nextPage']; ?>" class="btn btn-primary pull-right">
        <i class="fa fa-forward"></i> Next
    </a>
<?php endif; ?>



<?php require_once APPROOT . '/views/inc/footer.php' ?>