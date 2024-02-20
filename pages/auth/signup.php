<?php 
  isLoggedIn()? redirect("/"): "";
  require "parts/header.php";
  require "parts/navbar.php";
?>
  <div class="container my-5" style="max-width: 500px">
    <h1 class="text-center mb-3">Sign Up</h1>

    <div class="card p-4">
      <?php require "parts/message.php"; ?>
      <form method="POST" action="/signup_action">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input
            type="text"
            class="form-control"
            id="username"
            name="username"
          />
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="email" name="email" />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input
            type="password"
            class="form-control"
            id="password"
            name="password"
          />
        </div>
        <div class="mb-3">
          <label for="confirm_password" class="form-label">Confirm Password</label>
          <input
            type="password"
            class="form-control"
            id="confirm_password"
            name="confirm_password"
          />
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Create Account</button>
        </div>
      </form>
    </div>

    <!-- links -->
    <div class="d-flex justify-content-between mt-3">
      <a href="/" class="text-decoration-none small"
        ><i class="bi bi-arrow-left-circle"></i> Back to Home</a
      >
      <a href="/login" class="text-decoration-none small"
        >Have an account? Log in here <i class="bi bi-arrow-right-circle"></i
      ></a>
    </div>
  </div>
<?php require "parts/footer.php"; ?>
