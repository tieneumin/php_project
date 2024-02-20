<?php
  !isLoggedIn()? redirect("/login"): "";

  // connect to database
  $database = connectToDB();

  // capture id from GET
  $id = $_GET["id"];

  // query for target post
  $sql = "SELECT * FROM posts WHERE id = :id"; 
  $query = $database -> prepare($sql);
  $query -> execute(["id" => $id]);
  $post = $query -> fetch();

  // prevent user URL manipulation
  // for removed posts
  isUser() && ($post["status"] === "removed")? redirect("/"): "";
  // for posts not by them
  isUser() && ($_SESSION["user"]["id"] !== $post["user_id"])? redirect("/"): "";

  require "parts/header.php";
  require "parts/navbar.php";
?>
  <div class="container my-5" style="max-width: 750px">
    <h1 class="mb-3">Edit Post</h1>
    <div class="card p-4">
      <?php require "parts/message.php"; ?>

      <form method="POST" action="/editpost_action">
        <div class="mb-3">
          <label for="title" class="form-label">Title</label>
          <input
            type="text"
            class="form-control"
            id="title"
            name="title"
            value="<?= $post["title"]; ?>"
          />
        </div>
        <div class="mb-3">
          <label for="content" class="form-label">Content</label>
          <textarea 
            class="form-control"
            id="content"
            rows="10"
            name="content"><?= $post["content"]; ?></textarea>
        </div>
        <div class="mb-3">
          <label for="flair" class="form-label">Flair</label>
          <input
            type="text"
            class="form-control"
            id="flair"
            name="flair"
            value="<?= $post["flair"]; ?>"
          />
        </div>
        <div class="mb-3">
          <label for="image" class="form-label">Image</label>
          <input
            type="text"
            class="form-control"
            id="image"
            name="image"
            value="<?= $post["image"]; ?>"  
          />
        </div>  
        <!-- hide status from users -->
        <?php if (!isUser()): ?>
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select 
              class="form-control" 
              id="status"
              name="status"
            >
              <option value="posted" 
                <?= $post["status"] === "posted"? "selected": "" ?>
              >Posted</option>
              <option value="removed" 
                <?= $post["status"] === "removed"? "selected": "" ?>
              >Removed</option>
            </select>
          </div>
        <?php else: ?>
          <input 
            type="hidden"
            name="status"
            value="<?= $post["status"]; ?>"
          />
        <?php endif; ?>
        <div class="d-grid">
          <input
            type="hidden"
            name="id"
            value="<?= $post["id"]; ?>"
          />
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>

    </div>
    <div class="text-center mt-3">
      <a href="/manageposts" class="text-decoration-none small"
        ><i class="bi bi-arrow-left-circle"></i> Back to Manage Posts</a
      >
    </div>
  </div>
<?php require "parts/footer.php"; ?>