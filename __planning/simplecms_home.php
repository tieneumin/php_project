<?php 
  $database = connectToDB();

  // query for all published posts in reverse order
  $sql = "SELECT * FROM posts WHERE status = :status ORDER BY id DESC"; 
  $query = $database -> prepare($sql);
  $query -> execute(["status" => "published"]);
  $posts = $query -> fetchAll();

  require "parts/header.php";
?> 
  <div class="container mx-auto my-5" style="max-width: 500px;">
    <h1 class="h1 mb-4 text-center">
      <?php if (isLoggedIn()): ?>
        <?= $_SESSION["user"]["name"]; ?>'s Blog
      <?php else: ?>
        Simple CMS
      <?php endif; ?>
    </h1>

    <?php foreach ($posts as $post): ?>
      <div class="card mb-2">
        <div class="card-body">
          <h5 class="card-title"><?= $post["title"]; ?></h5>
          <p class="card-text"><?= nl2br($post["content"]); ?></p>
          <div class="text-end">
            <a href="/post?id=<?= $post["id"]; ?>" class="btn btn-primary btn-sm">Read More</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <div class="mt-4 d-flex justify-content-center gap-3">
      <!-- logged in vs signed out links -->
      <?php if (isLoggedIn()): ?>
        <a href="/dashboard" class="btn btn-link btn-sm">Dashboard</a>
        <a href="/logout_action" class="btn btn-link btn-sm">Logout</a>
      <?php else: ?>
        <a href="/login" class="btn btn-link btn-sm">Login</a>
        <a href="/signup" class="btn btn-link btn-sm">Sign Up</a>
      <?php endif; ?>
    </div>
  </div>

<?php require "parts/footer.php"; ?>