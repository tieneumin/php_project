<?php
  $database = connectToDB();

  // capture data from GET
  $post_id = isset($_GET["id"])? $_GET["id"]: "";

  // query for target post; admin/moderator can see removed posts
  if (isAdmin() || isModerator()) {
    $sql = "SELECT posts.*, users.username AS username
      FROM posts JOIN users ON posts.user_id = users.id
      WHERE posts.id = :post_id";
    $query = $database -> prepare($sql);
    $query -> execute([
      "post_id" => $post_id,
    ]);
  } else {
    $sql = "SELECT posts.*, users.username AS username
      FROM posts JOIN users ON posts.user_id = users.id
      WHERE posts.id = :post_id AND status = :status"; 
    $query = $database -> prepare($sql);
    $query -> execute([
      "post_id" => $post_id,
      "status" => "posted"
    ]);
  }
  $post = $query -> fetch();

  // redirect if post not found
  !$post? redirect("/"): "";

  // query for target post comments
  $sql = "SELECT comments.*, users.username AS username
    FROM comments JOIN users ON comments.user_id = users.id
    WHERE post_id = :post_id"; 
  $query = $database -> prepare($sql);
  $query -> execute(["post_id" => $post_id]);
  $comments = $query -> fetchAll(); // ALL comments

  if (isLoggedIn()) {
    // query for post bookmark status
    $sql = "SELECT * FROM bookmarks
      WHERE post_id = :post_id AND user_id = :user_id AND comment_id IS NULL"; 
    $query = $database -> prepare($sql);
    $query -> execute([
      "post_id" => $post_id,
      "user_id" => $_SESSION["user"]["id"]
    ]);
    $bookmark = $query -> fetch();
  }

  require "parts/header.php";
  require "parts/navbar.php";
?>
  <div class="container my-4" style="max-width: 750px">
    <h4 class="text-center text-break">
      <?= $post["title"]; ?>
      <span class="badge bg-secondary"><?= $post["flair"]; ?></span>
    </h4>
    <div class="mb-3 text-center">
      <!-- $post["username"] possible via AS, JOIN/ON -->
      <span class="fw-bold small">u/<?= $post["username"]; ?></span>
      <span class="text-muted small"> • <?= $post["added_on"]; ?> GMT</span>
    </div>
    <p><?= nl2br($post["content"]); ?></p>
    <img src="<?= $post["image"]; ?>" class="img-fluid d-block mx-auto mb-3" />

    <!-- actions -->
    <a href="/postbookmark_action?post_id=<?= $post["id"]; ?>" class="text-decoration-none text-black">
      <?php if (empty($bookmark) || !isLoggedIn()): ?>
        <i class="bi bi-bookmark"></i>
        <span class="fw-bold small">Bookmark</span>
      <?php else: ?>
        <i class="bi bi-bookmark-fill"></i>
        <span class="fw-bold small">Bookmarked</span>
      <?php endif; ?>
    </a>
    <hr>
      
    <!-- comments -->
    <h5 class="mb-3">Comments</h5>
    <?php foreach ($comments as $comment): ?>
      <div class="card mb-3">
        <div class="card-header">
          <span class="fw-bold small">u/<?= $comment["username"]; ?></span>
          <span class="text-muted small"> • <?= $comment["added_on"]; ?> GMT</span>
        </div>
        <div class="card-body">
          <?= nl2br($comment["content"]); ?>
        </div>
      </div>
    <?php endforeach; ?>

    <!-- comments form -->
    <?php if (isLoggedIn()): ?>
      <form action="/addcomment_action" method="POST">
        <div class="mb-3">
          <label for="content" class="form-label">Leave a comment:</label>
          <?php require "parts/message.php"; ?>
          <textarea class="form-control mb-3" id="content" rows="3" name="content"></textarea>
        </div>
        <div class="d-flex justify-content-end">
          <input type="hidden" name="post_id" value="<?= $post["id"]; ?>"/>
          <input type="hidden" name="user_id" value="<?= $_SESSION["user"]["id"]; ?>"/>
          <button type="submit" class="btn btn-primary">Post</button>
        </div>
      </form>
    <?php endif; ?>

    <div class="mt-3 text-center">
      <a href="/" class="text-decoration-none small"
        ><i class="bi bi-arrow-left-circle"></i> Back to Home</a
      >
    </div>
  </div>
<?php require "parts/footer.php"; ?>