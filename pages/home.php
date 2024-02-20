<?php 
  $database = connectToDB();

  // query for all posts in reverse order
  $sql = "SELECT posts.*, users.username AS username
    FROM posts JOIN users ON posts.user_id = users.id
    WHERE status = :status
    ORDER BY posts.id DESC"; 
  $query = $database -> prepare($sql);
  $query -> execute([
    "status" => "posted"
  ]);
  $posts = $query -> fetchAll();

  require "parts/header.php";
  require "parts/navbar.php";
?>
<style>
  .mouseover:hover {
    background-color: #e2e3e5;
  }
</style>
  <div class="container-fluid my-4">
    <?php foreach ($posts as $post): ?>
      <a href="/post?id=<?= $post["id"]; ?>" class="text-decoration-none">
        <div class="card mb-2 mouseover">
          <div class="card-header">
            <span class="fw-bold small">u/<?= $post["username"]; ?></span>
            <span class="text-muted small"> • <?= $post["added_on"]; ?> GMT</span>
          </div>
          <div class="card-body">
            <h5>
              <?= $post["title"]; ?>
              <span class="badge bg-secondary"><?= $post["flair"]; ?></span>
            </h5>
            <p><?= nl2br($post["content"]); ?></p>
          </div>
        </div>
      </a>
    <?php endforeach; ?>

    <div class="mt-4 d-flex justify-content-center gap-4">
      <!-- logged in vs signed out links -->
      <?php if (isLoggedIn()): ?>
        <a href="/dashboard" class="small">Dashboard</a>
        <a href="/logout_action" class="small">Logout</a>
      <?php else: ?>
        <a href="/login" class="small">Login</a>
        <a href="/signup" class="small">Sign Up</a>
      <?php endif; ?>
    </div>
  </div>

<?php require "parts/footer.php"; ?>