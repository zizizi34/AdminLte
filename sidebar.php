<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Fixed Sidebar - SMK Negeri 6 Surakarta</title>
  
  <!-- AdminLTE CSS -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css"
  />
  
  <!-- Bootstrap Icons -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css"
  />
  
  <!-- Font Awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />
  
  <style>
    /* Clean Sidebar Styling */
    .main-sidebar {
      background: #2c3e50;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }
    
    .brand-link {
      background: #34495e;
      border-bottom: 1px solid #3d566e;
      padding: 15px 20px;
    }
    
    .brand-text {
      color: #ecf0f1 !important;
      font-weight: 600;
      font-size: 16px;
    }
    
    .brand-image {
      border: 2px solid #3d566e;
    }
    
    /* Navigation */
    .sidebar {
      padding-top: 10px;
    }
    
    .nav-sidebar {
      padding: 0 15px;
    }
    
    .nav-item {
      margin-bottom: 2px;
    }
    
    .nav-link {
      color: #bdc3c7 !important;
      padding: 12px 15px;
      border-radius: 6px;
      transition: all 0.2s ease;
      margin-bottom: 2px;
    }
    
    .nav-link:hover {
      background: #34495e;
      color: #ffffff !important;
    }
    
    .nav-link.active {
      background: #3498db;
      color: #ffffff !important;
    }
    
    .nav-icon {
      width: 20px;
      text-align: center;
      margin-right: 10px;
      font-size: 16px;
    }
    
    /* TreeView */
    .nav-treeview {
      background: #243342;
      border-radius: 6px;
      margin: 5px 0;
      padding: 5px 0;
    }
    
    .nav-treeview .nav-link {
      padding: 10px 15px 10px 45px;
      font-size: 14px;
      margin-bottom: 1px;
    }
    
    .nav-treeview .nav-link:hover {
      background: #2c3e50;
    }
    
    .nav-treeview .nav-link.active {
      background: #2980b9;
    }
    
    /* Header */
    .nav-header {
      color: #95a5a6;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 15px 15px 8px 15px;
      margin-top: 10px;
    }
    
    /* Arrow icon for treeview */
    .has-treeview > .nav-link .right {
      transition: transform 0.2s ease;
    }
    
    .has-treeview.menu-open > .nav-link .right {
      transform: rotate(-90deg);
    }
    
    /* Logout special styling */
    .logout-link {
      border: 1px solid #e74c3c;
      background: rgba(231, 76, 60, 0.1);
    }
    
    .logout-link:hover {
      background: #e74c3c !important;
      color: #ffffff !important;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="./dashboard.php" class="brand-link">
        <img
          src="dist/assets/img/AdminLTELogo.png"
          alt="AdminLTE Logo"
          class="brand-image img-circle elevation-3"
          style="opacity: .8"
        />
        <span class="brand-text">SMKN 6 Surakarta</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul
            class="nav nav-pills nav-sidebar flex-column"
            data-widget="treeview"
            role="menu"
            data-accordion="false"
          >
            <!-- Dashboard -->
            <li class="nav-item">
              <a href="dashboard.php" class="nav-link">
                <i class="nav-icon bi bi-house-door"></i>
                <p>Dashboard</p>
              </a>
            </li>

            <!-- Data Section -->
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-database"></i>
                <p>
                  Data
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="datasiswa.php" class="nav-link">
                    <i class="nav-icon bi bi-people"></i>
                    <p>Data Siswa</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="datajurusan.php" class="nav-link">
                    <i class="nav-icon bi bi-mortarboard"></i>
                    <p>Data Jurusan</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="dataagama.php" class="nav-link">
                    <i class="nav-icon bi bi-moon-stars"></i>
                    <p>Data Agama</p>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Forms Section -->
            <?php if ($role !== 'siswa'): ?>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-plus-circle"></i>
                <p>
                  Forms
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="tambahsiswa.php" class="nav-link">
                    <i class="nav-icon bi bi-person-plus"></i>
                    <p>Tambah Siswa</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="tambahjurusan.php" class="nav-link">
                    <i class="nav-icon bi bi-book"></i>
                    <p>Tambah Jurusan</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="tambahagama.php" class="nav-link">
                    <i class="nav-icon bi bi-plus-square"></i>
                    <p>Tambah Agama</p>
                  </a>
                </li>
              </ul>
            </li>
            <?php endif; ?>

            <!-- Users Header -->
            <li class="nav-header">USERS</li>

            <!-- Auth Section -->
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-person-gear"></i>
                <p>
                  Auth
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="profile.php" class="nav-link">
                    <i class="nav-icon bi bi-person-circle"></i>
                    <p>Profile</p>
                  </a>
                </li>
                <?php if ($role !== 'siswa'): ?>
                <li class="nav-item">
                  <a href="usermanagement.php" class="nav-link">
                    <i class="nav-icon bi bi-people-fill"></i>
                    <p>User Management</p>
                  </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                  <a
                    href="logout.php"
                    class="nav-link logout-link"
                    onclick="return confirm('Apakah kamu yakin ingin keluar?')"
                  >
                    <i class="nav-icon bi bi-box-arrow-right"></i>
                    <p>Sign Out</p>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
      </div>
    </aside>

    <!-- jQuery (required by AdminLTE) -->
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"
    ></script>

    <!-- Bootstrap 5 Bundle JS -->
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"
    ></script>

    <!-- AdminLTE JS -->
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"
    ></script>

    <script>
      $(document).ready(function () {
        // Highlight menu sesuai halaman aktif otomatis
        var current = location.pathname.split('/').pop();

        $('.nav-link').each(function () {
          var $this = $(this);
          if ($this.attr('href') === current) {
            $this.addClass('active');

            var treeview = $this.closest('.nav-treeview');
            if (treeview.length) {
              treeview.addClass('menu-open');
              treeview
                .closest('.has-treeview')
                .find('> a.nav-link')
                .addClass('active');
            }
          }
        });
      });
    </script>
  </div>
</body>
</html>