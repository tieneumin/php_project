<?php
  !isAdmin()? redirect("/login"): "";
  
  // connect to database
  $database = connectToDB();
  
  // query for all users in descending order
  $sql = "SELECT * FROM users ORDER BY id DESC"; 
  $query = $database -> prepare($sql);
  $query -> execute();
  $users = $query -> fetchAll(); // ALL users

  require "parts/header.php";
  require "parts/navbar.php";
?>
  <div class="container my-5" style="max-width: 750px">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1>Manage Users</h1>
      <a href="/adduser" class="btn btn-primary">Add User</a>
    </div>
    <div class="card p-4">
      <?php require "parts/message.php"; ?>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>

          <!-- array_reverse(foreach) if "ORDER BY id DESC" not used above -->
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?= $user["id"]; ?></td>
              <td><?= $user["username"]; ?></td>
              <td><?= $user["email"]; ?></td>
              <td>
                <!-- role-based formatting -->
                <?php if ($user["role"] === "admin"): ?>
                  <span class="badge bg-info">Admin</span>
                <?php elseif ($user["role"] === "moderator"): ?>
                  <span class="badge bg-primary">Moderator</span>
                <?php elseif ($user["role"] === "user"): ?>
                  <span class="badge bg-secondary">User</span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <!-- edit button 
                  GET format required for edit/changepwd buttons to handle both
                  managePage -> editPage AND updateAction (cannot POST) -> editPage scenarios
                -->
                <a
                  href="/edituser?id=<?= $user["id"]; ?>"
                  class="btn btn-success btn-sm me-2"
                  ><i class="bi bi-pencil"></i
                ></a>
                <!-- changepwd button -->
                <a
                  href="/changepassword?id=<?= $user["id"]; ?>"
                  class="btn btn-warning btn-sm me-2"
                  ><i class="bi bi-key"></i
                ></a>
                <!-- delete button
                  - "disabled" attribute for current user to prevent self-deletion
                  - ensure unique modal targeted to avoid potential issues 
                -->
                <button 
                  <?= $user["id"] === $_SESSION["user"]["id"]? "disabled": "" ?>
                  type="button"
                  class="btn btn-danger btn-sm"
                  data-bs-toggle="modal"
                  data-bs-target="#deleteUserModal<?= $user["id"]; ?>"
                  ><i class="bi bi-trash"></i
                ></button>

                <!-- delete modal -->
                <div class="modal fade text-start" id="deleteUserModal<?= $user["id"]; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5>Are you sure you want to delete this user?<br>(<?= $user["email"]; ?>)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        This action cannot be reversed.
                      </div>
                      <div class="modal-footer">
                        <form method="POST" action="/deleteuser_action">
                          <input 
                            type="hidden"
                            name="id"
                            value="<?= $user["id"]; ?>"
                          />
                          <!-- forms do not work if button w attribute type="button" used -->
                          <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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