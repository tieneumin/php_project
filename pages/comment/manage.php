<?php
  !isLoggedIn()? redirect("/login"): "";

  // connect to database
  $database = connectToDB();

  // admin/moderator see all comments, users see own comments
  if (!isUser()) {
    $sql = "SELECT comments.*, users.username, posts.status FROM comments
      JOIN users ON comments.user_id = users.id
      JOIN posts ON comments.post_id = posts.id
      ORDER BY id DESC";
    $query = $database -> prepare($sql);
    $query -> execute();
  } else {  
    $sql = "SELECT comments.*, posts.status FROM comments
      JOIN posts ON comments.post_id = posts.id
      WHERE comments.user_id = :user_id
      ORDER BY id DESC";
    $query = $database -> prepare($sql);
    $query -> execute(["user_id" => $_SESSION["user"]["id"]]);
  }
  $comments = $query -> fetchAll(); // ALL comments
    
  require "parts/header.php";
  require "parts/navbar.php"; 
?>
  <div class="container my-5" style="max-width: 1000px">
    <h1 class="mb-3">Manage Comments</h1>
    <div class="card p-4">
      <?php require "parts/message.php"; ?>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th style="width: 60%">Content</th>
            <?php if (!isUser()): ?>
              <th>Username</th>
            <?php endif; ?>
            <th>Status</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>

          <!-- array_reverse(foreach) if "ORDER BY id DESC" not used above -->
          <?php foreach ($comments as $comment): ?>
            <tr>
              <td><?= $comment["id"]; ?></th>
              <td>
                <?php limitCharCount($comment["content"], 35); ?>
              </td>
              <?php if (!isUser()): ?>
                <!-- possible via JOIN/ON -->
                <td><?= $comment["username"]; ?></td>
              <?php endif; ?>
              <td>
                <!-- status-based formatting -->
                <?php if ($comment["removed"] == 0): ?>
                  <span class="badge bg-success">Commented</span>
                <?php elseif ($comment["removed"] == 1): ?>
                  <span class="badge bg-danger">Removed</span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <!-- view button; disabled for users if post/comment removed -->
                <a
                  href="/post?id=<?= $comment["post_id"]; ?>"
                  target="_blank"
                  class="btn btn-primary btn-sm me-2 <?= isUser() && (($comment["status"] === "removed") || ($comment["removed"] == 1))? "disabled": "" ?>"
                  ><i class="bi bi-eye"></i
                ><a>
                <!-- edit button; disabled for users if post/comment removed -->
                <a
                  href="/editcomment?id=<?= $comment["id"]; ?>"
                  class="btn btn-secondary btn-sm me-2 <?= isUser() && (($comment["status"] === "removed") || ($comment["removed"] == 1))? "disabled": "" ?>"
                  ><i class="bi bi-pencil"></i
                ></a>
                <!-- delete button; ensure unique modal targeted to avoid potential issues -->
                <button
                  type="button"
                  class="btn btn-danger btn-sm"
                  data-bs-toggle="modal"
                  data-bs-target="#deleteCommentModal<?= $comment["id"]; ?>"
                  ><i class="bi bi-trash"></i
                ></button>

                <!-- delete modal -->
                <div class="modal fade text-start" id="deleteCommentModal<?= $comment["id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5>Are you sure you want to delete this comment?
                          <br>(<?php limitCharCount($comment["content"], 21); ?>)
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        This action cannot be reversed.
                      </div>
                      <div class="modal-footer">
                        <form method="POST" action="/deletecomment_action">
                          <input 
                            type="hidden"
                            name="id"
                            value="<?= $comment["id"]; ?>"
                          />
                          <!-- forms do not work if button w attribute type="button" used -->
                          <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

              </td>
            </tr>
          <?php endforeach; ?>

        </tbody>
      </table>
    </div>
    <div class="text-center mt-3">
      <a href="/dashboard" class="text-decoration-none small"
        ><i class="bi bi-arrow-left-circle"></i> Back to Dashboard</a
      >
    </div>
  </div>
<?php require "parts/footer.php"; ?>