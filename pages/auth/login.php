<?php 
  isLoggedIn()? redirect("/"): "";
  require "parts/header.php";
  require "parts/navbar.php";
?>
  <div class="container my-5" style="max-width: 500px">
    <h1 class="text-center mb-3">Login</h1>

    <div class="card p-4">
      <?php require "parts/message.php"; ?>
      <form method="POST" action="/login_action">
        <input
          type="text"
          class="form-control mb-3"
          id="email"
          placeholder="Email address"
          name="email"
        />
        <input
          type="password"
          class="form-control mb-3"
          id="password"
          placeholder="Password"
          name="password"
        />
        <div class="d-grid">
          <button type="submit" class="btn btn-primary d-grid">Log In</button>
        </div>
      </form>
    </div>

    <!-- links -->
    <div class="d-flex justify-content-between mt-3">
      <a href="/" class="text-decoration-none small"
        ><i class="bi bi-arrow-left-circle"></i> Back to Home</a
      >
      <a href="/signup" class="text-decoration-none small"
        >Don't have an account? Sign up here
        <i class="bi bi-arrow-right-circle"></i
      ></a>
    </div>
  </div>
<?php require "parts/footer.php"; ?>
