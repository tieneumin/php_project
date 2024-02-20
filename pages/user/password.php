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
    <h1 class="mb-3">Change Password for <?= $user["username"]; ?></h1>
    <div class="card p-4">
      <?php require "parts/message.php"; ?>

      <form method="POST" action="changepassword_action">
        <div class="row mb-3">
          <div class="col">
            <label for="password" class="form-label">New Password</label>
            <input
              type="password"
              class="form-control"
              id="password"
              name="password"
            />
          </div>
          <div class="col">
            <label for="confirm_password" class="form-label"
              >Confirm Password</label
            >
            <input
              type="password"
              class="form-control"
              id="confirm_password"
              name="confirm_password"
            />
          </div>
        </div>
        <div class="d-grid">
          <input 
            type="hidden"
            name="id"
            value="<?= $user["id"]; ?>"
          />
          <button type="submit" class="btn btn-primary">
            Update
          </button>
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