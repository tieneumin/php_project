<nav class="navbar bg-body-tertiary sticky-top border-bottom">
  <div class="container-fluid">
    <a class="navbar-brand" href="/">icodedit</a>
    <button
      type="button"
      class="navbar-toggler"
      data-bs-toggle="offcanvas"
      data-bs-target="#offcanvasMenu"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end" id="offcanvasMenu">
      <div class="offcanvas-header pb-1">
        <h5 class="offcanvas-title">Menu</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="offcanvas"
        ></button>
      </div>
      <?php if (isLoggedIn()): ?>
        <div class="px-3 py-1">
          <a href="/createpost" class="btn btn-primary">Create Post</a>
        </div>
      <?php endif; ?>
      <div class="offcanvas-body pt-1">
        <ul class="navbar-nav">
          <?php if (isLoggedIn()): ?>
            <li class="nav-item">
              <a class="nav-link" href="/">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/dashboard">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/bookmarks">Bookmarks</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/settings">Settings</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/logout_action">Logout</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="/signup">Sign Up</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/login">Login</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</nav>