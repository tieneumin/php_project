<?php 
  !isLoggedIn()? redirect("/login"): "";

  // connect to database
  $database = connectToDB();

  // query for all posts/comments bookmarked by user in reverse order
  $sql = "SELECT bookmarks.*, posts.title, posts.content AS postContent, posts.flair, posts.status, posts.added_on AS postedOn,
    comments.content AS commentContent, comments.removed, comments.added_on AS commentedOn, users.username FROM bookmarks
    LEFT JOIN posts ON bookmarks.post_id = posts.id
    LEFT JOIN comments ON bookmarks.comment_id = comments.id 
    JOIN users ON bookmarks.user_id = users.id
    WHERE bookmarks.user_id = :user_id
    ORDER BY bookmarks.id DESC";
  $query = $database -> prepare($sql);
  $query -> execute([
    "user_id" => $_SESSION["user"]["id"]
  ]);
  $bookmarks = $query -> fetchAll();

  require "parts/header.php";
  require "parts/navbar.php";
?> 
  <div class="container my-5" style="max-width: 1000px;">
    <h1 class="text-center mb-3">
        <?= $_SESSION["user"]["username"]; ?>'s Bookmarks
    </h1>

    <?php foreach ($bookmarks as $bookmark): ?>
      <!-- bookmarked posts: admin/moderator see all; users only see unremoved -->
      <!-- && evaluated first -->
      <?php if (!isset($bookmark["comment_id"]) && $bookmark["status"] === "posted" || !isUser()): ?>
        <div class="card mb-3">
          <div class="card-header">
            <span class="fw-bold small">u/<?= $bookmark["username"]; ?></span>
            <span class="text-muted small"> • <?= $bookmark["postedOn"]; ?> GMT</span>
          </div>
          <div class="card-body">
            <h5>
              <?= $bookmark["title"]; ?>
              <span class="badge bg-secondary"><?= $bookmark["flair"]; ?></span>
            </h5>
            <p><?= nl2br($bookmark["postContent"]); ?></p>
            <div class="d-flex">
              <a href="/post?id=<?= $bookmark["post_id"]; ?>" class="btn btn-primary btn-sm me-2">View Post</a>
              <form method="POST" action="deletebookmark_action">
                <input
                  type="hidden"
                  name="bookmark_id"
                  value="<?= $bookmark["id"]; ?>"
                />
                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </div>
        </div>

      <!-- bookmarked comments: admin/moderator see all, users see unremoved comments from unremoved posts -->
      <!-- ANDs are evaluated first -->
      <?php elseif (isset($bookmark["comment_id"]) && $bookmark["status"] === "posted" && $bookmark["removed"] == 0 || !isUser()): ?>
        <div class="card mb-3">
          <div class="card-header">
            <span class="fw-bold small">u/<?= $bookmark["username"]; ?></span>
            <span class="text-muted small"> • <?= $bookmark["commentedOn"]; ?> GMT</span>
          </div>
          <div class="card-body">
            <p><?= nl2br($bookmark["commentContent"]); ?></p>
            <div class="d-flex">
              <a href="/post?id=<?= $bookmark["post_id"]; ?>" class="btn btn-primary btn-sm me-2">See Context</a>
              <form method="POST" action="deletebookmark_action">
                <input
                  type="hidden"
                  name="bookmark_id"
                  value="<?= $bookmark["id"]; ?>"
                />
                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </div>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>

    <div class="text-center mt-3">
      <a href="/dashboard" class="text-decoration-none small"
        ><i class="bi bi-arrow-left-circle"></i> Back to Dashboard</a
      >
    </div>
  </div>
<?php require "parts/footer.php"; ?>