<?php require APPROOT . '/views/inc/header.php'; ?>
    <a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i>Back</a>
      <div class="card card-body bg-light mt-5">
        <h2>Add Comment</h2>
        <p>Create a comment with that form</p>
        <form action="<?php echo URLROOT; ?>/comments/addCommentToPost/<?php echo $data['post_id']?>" method="post">
          <div class="form-group">
            <label for="text">Comment: <sup>*</sup></label>
            <textarea name="text" class="form-control form-control-lg <?php echo (!empty($data['text_err'])) ? 'is-invalid' : ''; ?>"><?php echo $data['text']; ?></textarea>
            <span class="invalid-feedback"><?php echo $data['text_err']; ?></span>
          </div>
          <input type="submit" value="Add" class="btn btn-success" />
        </form>
      </div>
    
<?php require APPROOT . '/views/inc/footer.php'; ?>