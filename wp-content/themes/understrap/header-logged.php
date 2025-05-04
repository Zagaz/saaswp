<?php
get_template_part('template-parts/header-common');
?>

<div class="container">
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
    <li class="nav-item">
        <a class="nav-link" href="?p=dashboard"><i class="fa-solid fa-house"></i> Home</a>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Registration
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
          <li><a class="dropdown-item" href="?p=add-income&a=add">Add Income</a></li>
          <li><a class="dropdown-item" href="?p=add-outcome&a=add">Add Outcome</a></li>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Report
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
          <li><a class="dropdown-item" href="?p=report-income">My income bills</a></li>
          <li><a class="dropdown-item" href="?p=report-outcome">My outcome bills</a></li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li><a class="dropdown-item" href="?p=report-full">Full Report</a></li>
        </ul>
      </li>
    </ul>
    <!-- Logout button -->
    <ul class="navbar-nav ms-auto">
      <li class="nav-item">
        <a class="nav-link" id="logout-bt" href="javascript:void(0);">
          <i class="fa-solid fa-right-from-bracket"></i>  
          Logout
        </a>
      </li>
    </ul>
  </div>
</div>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Do you really want to logout?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirm-logout">Logout</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const logoutButton = document.getElementById('logout-bt');
  const confirmLogoutButton = document.getElementById('confirm-logout');

  // Show the modal when the logout button is clicked
  logoutButton.addEventListener('click', function () {
    const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
    logoutModal.show();
  });

  // Handle the logout action when the confirm button is clicked
  confirmLogoutButton.addEventListener('click', function () {
    window.location.href = "<?php echo wp_logout_url(home_url('/')); ?>";
  });
});
</script>
</nav>