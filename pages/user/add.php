<?php
  !isAdmin()? redirect("/login"): "";
  require "parts/header.php";
  require "parts/navbar.php";
?>
  <div class="container my-5" style="max-width: 750px">
    <h1 class="mb-3">Add User</h1>
    <div class="card p-4">
      <?php require "parts/message.php"; ?>

      <form method="POST" action="/adduser_action">
        <div class="row mb-3">
          <div class="col">
            <label for="username" class="form-label">Username</label>
            <input 
              type="text" 
              class="form-control" 
              id="username" 
              name="username" 
            />
          </div>
          <div class="col">
            <label for="email" class="form-label">Email</label>
            <input 
              type="email" 
              class="form-control" 
              id="email" 
              name="email" 
            />
          </div>
        </div>
        <div class="row mb-3">
          <div class="col">
            <label for="password" class="form-label">Password</label>
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
        <div class="mb-3">
          <label for="role" class="form-label">Role</label>
          <select
            class="form-control" 
            id="role" 
            name="role"
          >
            <option value="">Select an option</option>
            <option value="user">User</option>
            <option value="moderator">Moderator</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Add</button>
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