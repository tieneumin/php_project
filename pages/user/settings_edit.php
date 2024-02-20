<?php
  !isLoggedIn()? redirect("/login"): "";
  require "parts/header.php";
  require "parts/navbar.php";
?>
  <div class="container my-5" style="max-width: 750px">
    <h1 class="text-center mb-3">Settings</h1>
    <div class="card p-4">
      <?php require "parts/message.php"; ?>

      <form method="POST" action="/settings_action">
        <div class="row mb-3">
          <div class="col">
            <label for="username" class="form-label">Username</label>
            <input 
              type="text"
              class="form-control" 
              id="username" 
              name="username"
              value="<?= $_SESSION["user"]["username"]; ?>" 
            />
          </div>
          <div class="col">
            <label for="email" class="form-label">Email</label>
            <input 
              type="email"
              class="form-control" 
              id="email" 
              name="email"
              value="<?= $_SESSION["user"]["email"]; ?>" 
            />
          </div>
        </div>
        <div class="d-grid">
          <input 
            type="hidden"
            name="id" 
            value="<?= $_SESSION["user"]["id"]; ?>"
          />
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>

    </div>
    <div class="d-flex justify-content-between mt-3">
      <a href="/dashboard" class="text-decoration-none small"
        ><i class="bi bi-arrow-left-circle"></i> Back to Dashboard</a
      >
      <a href="/settings_password" class="text-decoration-none small"
        >Change Password <i class="bi bi-arrow-right-circle"></i
      ></a>
    </div>
  </div>
<?php require "parts/footer.php"; ?>