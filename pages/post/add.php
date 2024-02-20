<?php
  !isLoggedIn()? redirect("/login"): "";
  require "parts/header.php";
  require "parts/navbar.php";
?>
  <div class="container my-5" style="max-width: 750px">
    <h1 class="mb-3">Create Post</h1>
    <div class="card p-4">
      <?php require "parts/message.php"; ?>

      <form method="POST" action="/addpost_action">
        <div class="mb-3">
          <label for="title" class="form-label">Title</label>
          <input 
            type="text"
            class="form-control"
            id="title"
            name="title"
          >
        </div>
        <div class="mb-3">
          <label for="content" class="form-label">Content</label>
          <textarea
            class="form-control"
            id="content"
            rows="10"
            name="content"
          ></textarea>
        </div>
        <div class="mb-3">
          <label for="flair" class="form-label">Flair</label>
          <input 
            type="text"
            class="form-control"
            id="flair"
            name="flair"
          >
        </div>
        <div class="mb-3">
          <label for="image" class="form-label">Image</label>
          <input 
            type="text"
            class="form-control"
            id="image"
            name="image"
          >
        </div>
        <div class="d-grid">
          <input
            type="hidden"
            name="user_id"
            value="<?= $_SESSION["user"]["id"]; ?>"
          />
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>

    </div>
    <div class="text-center mt-3">
      <a href="/manageposts" class="text-decoration-none small"
        ><i class="bi bi-arrow-left-circle"></i> Back to Manage Posts</a
      >
    </div>
  </div>
<?php require "parts/footer.php"; ?>