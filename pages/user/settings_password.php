<?php
  !isLoggedIn()? redirect("/login"): "";
  require "parts/header.php";
  require "parts/navbar.php";
?>
  <div class="container my-5" style="max-width: 750px">
    <h1 class="text-center mb-3">Settings</h1>
    <div class="card p-4">
      <?php require "parts/message.php"; ?>
      
      <form method="POST" action="/settings_password_action">
        <div class="row mb-3">
          <div class="col">
            <label for="new_password" class="form-label">New Password</label>
            <input 
              type="password"
              class="form-control"
              id="new_password"
              name="new_password"
            />
          </div>
          <div class="col">
            <label for="confirm_password" class="form-label">Confirm New Password</label>
            <input 
              type="password" 
              class="form-control" 
              id="confirm_password"
              name="confirm_password"
            />
          </div>
        </div>
        <div class="mb-3">
          <label for="current_password" class="form-label">Current Password</label>
          <input 
            type="password" 
            class="form-control" 
            id="current_password"
            name="current_password"
          />
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
      <a href="/settings" class="text-decoration-none small"
        >Change User Details <i class="bi bi-arrow-right-circle"></i
      ></a>
    </div>
  </div>
<?php require "parts/footer.php"; ?>