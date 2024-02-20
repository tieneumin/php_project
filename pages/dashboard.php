<?php
  !isLoggedIn()? redirect("/login"): "";
  require "parts/header.php";
  require "parts/navbar.php";
?>
  <div class="container my-5" style="max-width: 750px">
    <h1 class="text-center mb-3">Dashboard</h1>

    <div class="row">
      <div class="col">
        <div class="card p-4">
          <div class="card-body text-center">
            <p class="bi bi-pencil-square fs-1"></p>
            <a href="/manageposts" class="btn btn-primary"> Manage Posts </a>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card p-4">
          <div class="card-body text-center">
            <p class="bi bi-chat-left-dots fs-1"></p>
            <a href="/managecomments" class="btn btn-primary">
              Manage Comments
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col">
        <div class="card p-4">
          <div class="card-body text-center">
            <p class="bi bi-bookmarks fs-1"></p>
            <a href="/bookmarks" class="btn btn-primary"> Bookmarks </a>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card p-4">
          <div class="card-body text-center">
            <p class="bi bi-person-gear fs-1"></p>
            <a href="/settings" class="btn btn-primary"> User Settings </a>
          </div>
        </div>
      </div>
    </div>
    <?php if (isAdmin()): ?>
      <div class="card p-4 mt-3">
        <div class="card-body text-center">
          <p class="bi bi-people fs-1"></p>
          <a href="/manageusers" class="btn btn-primary"> Manage Users </a>
        </div>
      </div>
    <?php endif; ?>

    <div class="text-center mt-3">
      <a href="/" class="text-decoration-none small"
        ><i class="bi bi-arrow-left-circle"></i> Back to Home</a
      >
    </div>
  </div>
<?php require "parts/footer.php"; ?>