<!-- show error if $_SESSION["error"] has value -->
<?php if (isset($_SESSION["error"])): ?>
    
  <div class="alert alert-danger" role="alert">
    <?= $_SESSION["error"]; ?>
  </div>

  <!-- clear error once shown to prevent reappearance on page revisit -->
  <?php unset($_SESSION["error"]); ?>

<!-- show success instead if $_SESSION["success"] has value  -->
<?php elseif (isset($_SESSION["success"])): ?>

  <div class="alert alert-success" role="alert">
    <?= $_SESSION["success"]; ?>
  </div>
  
  <!-- clear success once shown to prevent reappearance on page revisit -->
  <?php unset($_SESSION["success"]); ?>

<?php endif; ?>