<?php
  !isAdmin()? redirect("/login"): "";

  // connect to database
  $database = connectToDB();
  
  // capture id from GET
  $id = $_GET["id"];

  // query for target user
  $sql = "SELECT * FROM users WHERE id = :id"; 
  $query = $database -> prepare($sql);
  $query -> execute(["id" => $id]);
  $user = $query -> fetch();
  
  require "parts/header.php";
  require "parts/navbar.php";
?>
  <div class="container my-5" style="max-width: 750px">
    <h1 class="mb-3">Edit User</h1>
    <div class="card p-4">
      <?php require "parts/message.php"; ?>

      <form method="POST" action="/edituser_action">
        <div class="row mb-3">
          <div class="col">
            <label for="username" class="form-label">Username</label>
            <input 
              type="text"
              class="form-control"
              id="username"
              name="username"
              value="<?= $user["username"]; ?>"
            />
          </div>
          <div class="col">
            <label for="email" class="form-label">Email</label>
            <input 
              type="email" 
              class="form-control" 
              id="email"
              name="email"
              value="<?= $user["email"]; ?>"
            />
          </div>
        </div>
        <div class="mb-3">
          <label for="role" class="form-label">Role</label>
          <select 
            class="form-control" 
            id="role"
            name="role"
          >
            <!-- assign "selected" attribute to preselect option based on role -->
            <option value="user"
              <?= $user["role"] === "user"? "selected": "" ?>
            >User</option>
            <option value="moderator" 
              <?= $user["role"] === "moderator"? "selected": "" ?> 
            >Moderator</option>
            <option value="admin"
              <?= $user["role"] === "admin"? "selected": "" ?> 
            >Admin</option>
          </select>
        </div>
        <div class="d-grid">
          <input
            type="hidden"
            name="id"
            value="<?= $user["id"]; ?>"
          />
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
      
    </div>
    <div class="text-center mt-3">
      <a href="/manageusers" class="text-decoration-none small"
        ><i class="bi bi-arrow-left-circle"></i> Back to Manage Users</a
      >
    </div>
  </div>
<?php require "parts/footer.php"; ?>