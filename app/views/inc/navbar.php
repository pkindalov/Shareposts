<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
  <div class="container">
    <a class="navbar-brand" href="<?php echo URLROOT; ?>"><?php echo SITENAME; ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URLROOT; ?>">Home</a>
        </li>

        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] == ADMIN) : ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/listUsers">Users</a>
          </li>

          <!-- <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/comments/commentsForApproving/1">Comments</a>
          </li> -->

          <?php if (isset($data['notApprovedPostsCount']) && $data['notApprovedPostsCount'] > 0) : ?>


            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT; ?>/posts/postsForApproving/1">
                Posts
                <span class="badge badge-light">
                  (<?php echo $data['notApprovedPostsCount'] ?>)
                </span>
                <span class="sr-only">unread messages</span>
              </a>
            </li>



          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT; ?>/posts/postsForApproving/1">Posts</a>
            </li>
          <?php endif; ?>






          <?php if (isset($data['notApprovedCommentsCount']) && $data['notApprovedCommentsCount'] > 0) : ?>


            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT; ?>/comments/commentsForApproving/1">
                Comments
                <span class="badge badge-light">
                  (<?php echo $data['notApprovedCommentsCount'] ?>)
                </span>
                <span class="sr-only">unread messages</span>
              </a>
            </li>



          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT; ?>/comments/commentsForApproving/1">Comments</a>
            </li>
          <?php endif; ?>







        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link" href="<?php echo URLROOT; ?>/pages/about">About</a>
        </li>
      </ul>

      <ul class="navbar-nav ml-auto">
        <?php if (isset($_SESSION['user_id'])) : ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/showUser/<?php echo $_SESSION['user_id']; ?>">Wellcome,<?php echo $_SESSION['user_name']; ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/logout">Logout</a>
          </li>
        <?php else : ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/register">Register</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/login">Login</a>
          </li>
        <?php endif ?>
      </ul>
    </div>
  </div>
</nav>