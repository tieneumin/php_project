<?php
  !isLoggedIn()? redirect("/login"): "";

  // connect to database
  $database = connectToDB();

  // admin/moderator see all posts, users see own posts
  if (!isUser()) {
    $sql = "SELECT posts.*, users.username FROM posts 
      JOIN users ON posts.user_id = users.id
      ORDER BY id DESC";
    $query = $database -> prepare($sql);
    $query -> execute();
  } else {  
    $sql = "SELECT * FROM posts
      WHERE user_id = :user_id
      ORDER BY id DESC"; 
    $query = $database -> prepare($sql);
    $query -> execute([
      "user_id" => $_SESSION["user"]["id"],
    ]);
  }
  $posts = $query -> fetchAll(); // ALL posts
    
  require "parts/header.php";
  require "parts/navbar.php"; 
?>
  <div class="container my-5" style="max-width: 1000px">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1>Manage Posts</h1>
      <a href="/createpost" class="btn btn-primary">Create Post</a>
    </div>
    <div class="card p-4">
      <?php require "parts/message.php"; ?>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th style="width: 60%">Title</th>
            <?php if (!isUser()): ?>
              <th>Username</th>
            <?php endif; ?>
            <th>Status</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>

          <!-- array_reverse(foreach) if "ORDER BY id DESC" not used above -->
          <?php foreach ($posts as $post): ?>
            <tr>
              <td><?= $post["id"]; ?></th>
              <td>
                <?php limitCharCount($post["title"], 35); ?>
              </td>
              <?php if (!isUser()): ?>
                <!-- possible via AS, JOIN/ON -->
                <td><?= $post["username"]; ?></td>
              <?php endif; ?>
              <td>
                <!-- status-based formatting -->
                <?php if ($post["status"] === "posted"): ?>
                  <span class="badge bg-success">Posted</span>
                <?php elseif ($post["status"] === "removed"): ?>
                  <span class="badge bg-danger">Removed</span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <!-- view button; disabled for users if post removed -->
                <a
                  href="/post?id=<?= $post["id"]; ?>"
                  target="_blank"
                  class="btn btn-primary btn-sm me-2 <?= isUser() && ($post["status"] === "removed")? "disabled": "" ?>"
                  ><i class="bi bi-eye"></i
                ><a>
                <!-- edit button; disabled for users if post removed -->
                <a
                  href="/editpost?id=<?= $post["id"]; ?>"
                  class="btn btn-secondary btn-sm me-2 <?= isUser() && ($post["status"] === "removed")? "disabled": "" ?>"
                  ><i class="bi bi-pencil"></i
                ></a>
                <!-- delete button
                - ensure unique modal targeted to avoid potential issues -->
                <button 
                  type="button"
                  class="btn btn-danger btn-sm"
                  data-bs-toggle="modal"
                  data-bs-target="#deletePostModal<?= $post["id"]; ?>"
                  ><i class="bi bi-trash"></i
                ></button>

                <!-- delete modal -->
                <div class="modal fade text-start" id="deletePostModal<?= $post["id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5>Are you sure you want to delete this post?
                          <br>(<?php limitCharCount($post["title"], 21); ?>)
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        This action cannot be reversed.
                      </div>
                      <div class="modal-footer">
                        <form method="POST" action="/deletepost_action">
                          <input 
                            type="hidden"
                            name="id"
                            value="<?= $post["id"]; ?>"
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