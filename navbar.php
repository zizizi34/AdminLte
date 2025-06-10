<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "koneksi.php";
$db = new database();

$username = $_SESSION['username'] ?? 'Guest';
?>

<style>
  nav {
    z-index: 1000;
  }
</style>

<!-- Navbar AdminLTE 4 - Enhanced Responsive with Fixed Dropdown -->
<nav class="main-header navbar navbar-expand-lg navbar-white navbar-light shadow-sm border-bottom z-1000">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href ="#" role="button">
        <i class="bi bi-list"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="index.php" class="nav-link">
        <i class="bi bi-house-door me-1"></i> 
        <span class="d-none d-md-inline">Home</span>
      </a>
    </li>
  </ul>

  <!-- Brand/Logo for mobile (optional) -->
  <div class="navbar-brand d-block d-lg-none ms-auto me-auto">
    <span class="brand-text fw-bold">Admin Panel</span>
  </div>

  <!-- Mobile navbar toggler -->
  <button class="navbar-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Right navbar - Collapsible on mobile -->
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ms-auto">
      <!-- Mobile Home Link -->
      <li class="nav-item d-block d-sm-none">
        <a href="index.php" class="nav-link">
          <i class="bi bi-house-door me-2"></i> Home
        </a>
      </li>

      <!-- Dark Mode Toggle -->
      <li class="nav-item">
        <a class="nav-link" href="#" id="darkModeToggle" title="Toggle Dark Mode">
          <i class="bi bi-moon" id="darkModeIcon"></i>
          <span class="d-inline d-lg-none ms-2">Dark Mode</span>
        </a>
      </li>

      <!-- Fullscreen -->
      <li class="nav-item">
        <a class="nav-link" href="#" data-widget="fullscreen" role="button" title="Fullscreen">
          <i class="bi bi-arrows-fullscreen"></i>
          <span class="d-inline d-lg-none ms-2">Fullscreen</span>
        </a>
      </li>

      <!-- Profile Dropdown - FIXED -->
      <li class="nav-item dropdown user-menu position-relative">
        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="profileDropdown" role="button" aria-expanded="false">
          <i class="bi bi-person-circle me-2"></i>
          <span class="username-text"><?= htmlspecialchars($username) ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end profile-dropdown" aria-labelledby="profileDropdown">
          <!-- User header -->
          <li class="user-header bg-primary text-white text-center py-3">
            <div class="d-flex align-items-center justify-content-center mb-2">
              <i class="bi bi-person-circle" style="font-size: 2.5rem;"></i>
            </div>
            <p class="mb-0 fw-bold"><?= htmlspecialchars($username) ?></p>
            <small class="opacity-75">Administrator</small>
          </li>
          <!-- Menu Body -->
          <li class="dropdown-divider"></li>
          <!-- Menu Footer-->
          <li class="user-footer p-3">
            <div class="d-grid gap-2">
              <a href="profile.php" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-person me-1"></i> Profile
              </a>
              <form action="logout.php" method="POST" class="mb-0">
                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                  <i class="bi bi-box-arrow-right me-1"></i> Sign out
                </button>
              </form>
            </div>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<!-- Enhanced CSS with Fixed Dropdown Positioning -->
<style>
  /* Base responsive styles */
  .navbar-toggler {
    border: 1px solid #dee2e6;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
    background: transparent;
  }

  .navbar-toggler:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
  }

  .navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2833, 37, 41, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    width: 1.5em;
    height: 1.5em;
  }

  .brand-text {
    font-size: 1.1rem;
    color: #495057;
  }

  .username-text {
    max-width: 120px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  /* FIXED: Profile Dropdown Positioning */
  .user-menu {
    position: static !important;
  }

  .profile-dropdown {
    position: absolute !important;
    top: 100% !important;
    right: 0 !important;
    left: auto !important;
    z-index: 1050 !important;
    min-width: 280px !important;
    max-width: 320px !important;
    margin-top: 0.5rem !important;
    border: 1px solid rgba(0, 0, 0, 0.15) !important;
    border-radius: 0.5rem !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    background-color: #ffffff !important;
    transform: translateY(0) !important;
  }

  .profile-dropdown.show {
    display: block !important;
  }

  /* Prevent dropdown from causing horizontal overflow */
  @media (max-width: 576px) {
    .profile-dropdown {
      position: fixed !important;
      top: 60px !important;
      right: 10px !important;
      left: 10px !important;
      min-width: auto !important;
      max-width: none !important;
      width: calc(100% - 20px) !important;
      margin-top: 0 !important;
    }

    .main-header.navbar {
      padding: 0.5rem 1rem;
      position: relative;
      overflow: visible !important;
    }
    
    .username-text {
      max-width: 80px;
    }
  }

  @media (max-width: 768px) {
    .navbar-nav {
      padding-top: 0.5rem;
    }
    
    .navbar-nav .nav-item {
      margin-bottom: 0.25rem;
    }

    .profile-dropdown {
      right: 5px !important;
      min-width: 260px !important;
    }
  }

  @media (min-width: 992px) {
    .navbar-nav .nav-link {
      padding: 0.5rem 1rem;
    }

    .profile-dropdown {
      right: 0 !important;
      transform: translateX(0) !important;
    }
  }

  /* Ensure navbar doesn't cause overflow */
  .main-header.navbar {
    position: relative;
    overflow: visible;
  }

  .navbar-collapse {
    overflow: visible !important;
  }

  /* User header improvements */
  .user-header {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
    border-radius: 0.5rem 0.5rem 0 0;
  }

  .user-footer {
    background-color: #f8f9fa;
    border-radius: 0 0 0.5rem 0.5rem;
  }

  /* Dark mode styles */
  body.dark-mode {
    --bs-body-bg: #1a1a1a;
    --bs-body-color: #e0e0e0;
    --bs-border-color: #404040;
  }

  body.dark-mode .main-header.navbar {
    background-color: #2d2d2d !important;
    border-color: #404040 !important;
  }

  body.dark-mode .navbar-light .navbar-nav .nav-link,
  body.dark-mode .navbar-light .navbar-brand,
  body.dark-mode .brand-text {
    color: #e0e0e0 !important;
  }

  body.dark-mode .navbar-light .navbar-nav .nav-link:hover,
  body.dark-mode .navbar-light .navbar-nav .nav-link:focus {
    color: #ffffff !important;
    background-color: rgba(255, 255, 255, 0.1) !important;
    border-radius: 4px;
  }

  body.dark-mode .navbar-toggler {
    border-color: #6c757d !important;
  }

  body.dark-mode .navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28224, 224, 224, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
  }

  body.dark-mode .profile-dropdown {
    background-color: #333333 !important;
    border-color: #404040 !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5) !important;
  }

  body.dark-mode .dropdown-item {
    color: #e0e0e0 !important;
  }

  body.dark-mode .dropdown-item:hover,
  body.dark-mode .dropdown-item:focus {
    background-color: #404040 !important;
    color: #ffffff !important;
  }

  body.dark-mode .dropdown-divider {
    border-color: #404040 !important;
  }

  body.dark-mode .user-header {
    background: linear-gradient(135deg, #404040 0%, #2d2d2d 100%) !important;
  }

  body.dark-mode .user-footer {
    background-color: #2d2d2d !important;
  }

  body.dark-mode .btn-outline-secondary {
    border-color: #6c757d !important;
    color: #e0e0e0 !important;
  }

  body.dark-mode .btn-outline-secondary:hover {
    background-color: #6c757d !important;
    color: #ffffff !important;
  }

  body.dark-mode .btn-outline-danger {
    border-color: #dc3545 !important;
    color: #dc3545 !important;
  }

  body.dark-mode .btn-outline-danger:hover {
    background-color: #dc3545 !important;
    color: #ffffff !important;
  }

  /* Additional dark mode styles for other components */
  body.dark-mode .content-wrapper {
    background-color: #1a1a1a !important;
  }

  body.dark-mode .card {
    background-color: #2d2d2d !important;
    border-color: #404040 !important;
  }

  body.dark-mode .card-header {
    background-color: #333333 !important;
    border-color: #404040 !important;
    color: #e0e0e0 !important;
  }

  body.dark-mode .card-body {
    color: #e0e0e0 !important;
  }

  body.dark-mode .table,
  body.dark-mode .table th,
  body.dark-mode .table td {
    color: #e0e0e0 !important;
    background-color: transparent !important;
  }

  body.dark-mode .form-control,
  body.dark-mode .form-select {
    background-color: #333333 !important;
    border-color: #404040 !important;
    color: #e0e0e0 !important;
  }

  body.dark-mode .main-footer {
    background-color: #2d2d2d !important;
    border-color: #404040 !important;
    color: #e0e0e0 !important;
  }
</style>

<!-- Enhanced JavaScript with Fixed Dropdown Logic -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const profileDropdown = document.getElementById('profileDropdown');
    const dropdownMenu = document.querySelector('.profile-dropdown');
    
    // Dark Mode functionality
    if (localStorage.getItem('darkMode') === 'enabled') {
      document.body.classList.add('dark-mode');
      darkModeIcon.className = 'bi bi-sun';
    }

    toggleBtn.addEventListener('click', (e) => {
      e.preventDefault();
      
      document.body.classList.toggle('dark-mode');
      
      if (document.body.classList.contains('dark-mode')) {
        darkModeIcon.className = 'bi bi-sun';
        localStorage.setItem('darkMode', 'enabled');
      } else {
        darkModeIcon.className = 'bi bi-moon';
        localStorage.setItem('darkMode', 'disabled');
      }
    });

    // FIXED: Profile Dropdown Logic
    profileDropdown.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const isOpen = dropdownMenu.classList.contains('show');
      
      // Close all other dropdowns first
      document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
        if (menu !== dropdownMenu) {
          menu.classList.remove('show');
        }
      });
      
      // Toggle current dropdown
      if (isOpen) {
        dropdownMenu.classList.remove('show');
        profileDropdown.setAttribute('aria-expanded', 'false');
      } else {
        dropdownMenu.classList.add('show');
        profileDropdown.setAttribute('aria-expanded', 'true');
        
        // Ensure dropdown is positioned correctly
        adjustDropdownPosition();
      }
    });

    // Function to adjust dropdown position to prevent overflow
    function adjustDropdownPosition() {
      const rect = dropdownMenu.getBoundingClientRect();
      const viewportWidth = window.innerWidth;
      const viewportHeight = window.innerHeight;
      
      // Reset any previous adjustments
      dropdownMenu.style.transform = '';
      dropdownMenu.style.left = '';
      dropdownMenu.style.right = '';
      
      // Check for horizontal overflow
      if (rect.right > viewportWidth) {
        const overflowAmount = rect.right - viewportWidth + 10; // 10px padding
        dropdownMenu.style.transform = `translateX(-${overflowAmount}px)`;
      }
      
      // Check for vertical overflow (bottom of screen)
      if (rect.bottom > viewportHeight) {
        dropdownMenu.style.top = 'auto';
        dropdownMenu.style.bottom = '100%';
      }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
      if (!profileDropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
        dropdownMenu.classList.remove('show');
        profileDropdown.setAttribute('aria-expanded', 'false');
      }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
      if (dropdownMenu.classList.contains('show')) {
        adjustDropdownPosition();
      }
    });

    // Auto-collapse mobile menu when clicking outside
    const navbar = document.querySelector('.navbar-collapse');
    const toggler = document.querySelector('.navbar-toggler');
    
    document.addEventListener('click', function(event) {
      if (!navbar.contains(event.target) && !toggler.contains(event.target)) {
        if (navbar.classList.contains('show')) {
          // Use Bootstrap's collapse method if available
          const bsCollapse = bootstrap.Collapse.getInstance(navbar);
          if (bsCollapse) {
            bsCollapse.hide();
          } else {
            navbar.classList.remove('show');
          }
        }
      }
    });

    // Prevent dropdown menu from closing when clicking inside it
    dropdownMenu.addEventListener('click', function(e) {
      e.stopPropagation();
    });

    // Handle scroll to reposition dropdown if needed
    window.addEventListener('scroll', function() {
      if (dropdownMenu.classList.contains('show')) {
        adjustDropdownPosition();
      }
    });
  });
</script>