<?php
  !isLoggedIn()? redirect("/login"): "";

  // connect to database
  $database = connectToDB();

  // capture id from GET
  $id = $_GET["id"];

  // query for target comment
  $sql = "SELECT * FROM comments WHERE id = :id"; 
  $query = $database -> prepare($sql);
  $query -> execute(["id" => $id]);
  $comment = $query -> fetch();

  // prevent user URL manipulation
  // for removed comments
  isUser() && ($comment["removed"] == 1)? redirect("/"): "";
  // for comments not by them
  isUser() && ($_SESSION["user"]["id"] !== $comment["user_id"])? redirect("/"): "";

  require "parts/header.php";
  require "parts/navbar.php"; 
?>
  <div class="container my-5" style="max-width: 750px">
    <h1 class="mb-3">Edit Comment</h1>
    <div class="card p-4">
      <?php require "parts/message.php"; ?>

      <form method="POST" action="/editcomment_action">
        <div class="mb-3">
          <label for="content" class="form-label">Content</label>
          <textarea
            class="form-control"
            id="content"
            rows="5"
            name="content"
          ><?= $comment["content"]; ?></textarea>
        </div>
        <!-- hide status from users -->
        <?php if (!isUser()): ?>
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select 
              class="form-control" 
              id="status"
              name="removed"
            >
              <option value="0" 
                <?= $comment["removed"] == 0? "selected": "" ?>
              >Commented</option>
              <option value="1" 
                <?= $comment["removed"] == 1? "selected": "" ?>
              >Removed</option>
            </select>
          </div>
        <?php else: ?>
          <input 
            type="hidden"
            name="removed"
            value="<?= $comment["removed"]; ?>"
          />
        <?php endif; ?>
        <div class="d-grid">
          <input
            type="hidden"
            name="id"
            value="<?= $comment["id"]; ?>"
          />
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>

    </div>
    <div class="text-center mt-3">
      <a href="/managecomments" class="text-decoration-none small"
        ><i class="bi bi-arrow-left-circle"></i> Back to Comments</a
      >
    </div>
  </div>
<?php require "parts/footer.php"; ?>