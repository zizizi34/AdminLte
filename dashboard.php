<?php
session_start();

include 'koneksi.php';

$db = new database();

// Check database connection
if (!$db->koneksi) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    error_log("Login validation failed. Session data: " . print_r($_SESSION, true));
    header('Location: index.php');
    exit;
}

// Query untuk menghitung total dengan error handling
$q_siswa = mysqli_query($db->koneksi, "SELECT COUNT(*) as total FROM siswa");
if (!$q_siswa) {
    die("Query error: " . mysqli_error($db->koneksi));
}
$jumlah_siswa = mysqli_fetch_assoc($q_siswa)['total'];

$q_jurusan = mysqli_query($db->koneksi, "SELECT COUNT(*) as total FROM kodejurusan");
if (!$q_jurusan) {
    die("Query error: " . mysqli_error($db->koneksi));
}
$jumlah_jurusan = mysqli_fetch_assoc($q_jurusan)['total'];

$q_agama = mysqli_query($db->koneksi, "SELECT COUNT(*) as total FROM kodeagama");
if (!$q_agama) {
    die("Query error: " . mysqli_error($db->koneksi));
}
$jumlah_agama = mysqli_fetch_assoc($q_agama)['total'];

// Query untuk grafik gender dengan error handling
$q_gender = mysqli_query($db->koneksi, "SELECT jeniskelamin, COUNT(*) as jumlah FROM siswa GROUP BY jeniskelamin");
if (!$q_gender) {
    die("Query error: " . mysqli_error($db->koneksi));
}
$data_gender = [];
while($row = mysqli_fetch_assoc($q_gender)) {
    $data_gender[] = $row;
}

// Query untuk grafik jurusan (bar chart)
$q_jurusan_chart = mysqli_query($db->koneksi, "SELECT kj.namajurusan, COUNT(s.nisn) as jumlah 
                                                FROM kodejurusan kj 
                                                LEFT JOIN siswa s ON kj.kodejurusan = s.kodejurusan 
                                                GROUP BY kj.kodejurusan, kj.namajurusan 
                                                ORDER BY jumlah DESC");
if (!$q_jurusan_chart) {
    die("Query error: " . mysqli_error($db->koneksi));
}
$data_jurusan = [];
while($row = mysqli_fetch_assoc($q_jurusan_chart)) {
    $data_jurusan[] = $row;
}

// Query untuk grafik agama (bar chart)  
$q_agama_chart = mysqli_query($db->koneksi, "SELECT ka.namaagama, COUNT(s.nisn) as jumlah 
                                            FROM kodeagama ka 
                                            LEFT JOIN siswa s ON ka.kodeagama = s.agama 
                                            GROUP BY ka.kodeagama, ka.namaagama 
                                            ORDER BY jumlah DESC");
if (!$q_agama_chart) {
    die("Query error: " . mysqli_error($db->koneksi));
}
$data_agama = [];
while($row = mysqli_fetch_assoc($q_agama_chart)) {
    $data_agama[] = $row;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Dashboard - Sistem Informasi Siswa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Dashboard Sistem Informasi Siswa SMK Negeri 6 Surakarta" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.css" />
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    .chart-container {
      position: relative;
      height: 350px;
      padding: 20px;
    }
    .small-box {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s ease;
    }
    .small-box:hover {
      transform: translateY(-5px);
    }
    .card {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      border: none;
    }
    .card-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 10px 10px 0 0 !important;
    }
    .stats-row {
      margin-bottom: 30px;
    }
    .dashboard-title {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: bold;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <?php include "navbar.php"; ?>
    <?php include "sidebar.php"; ?>
<div class="content-wrapper">
  <main class="app-main">
    <div class="app-content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <p class="text-muted">Sistem Informasi Siswa SMK Negeri 6 Surakarta</p>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="app-content">
      <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row stats-row">
          <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary">
              <div class="inner">
                <h3><?= $jumlah_siswa; ?></h3>
                <p>Total Siswa</p>
              </div>
              <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
              <a href="datasiswa.php" class="small-box-footer">
                <i class="bi bi-arrow-right-circle"></i> Lihat Data Siswa
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
              <div class="inner">
                <h3><?= $jumlah_jurusan; ?></h3>
                <p>Total Jurusan</p>
              </div>
              <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L3 7v13h18V7l-9-5zM8 18v-5h8v5H8zm0-7v-3l4-2 4 2v3H8z"/>
              </svg>
              <a href="datajurusan.php" class="small-box-footer">
                <i class="bi bi-arrow-right-circle"></i> Lihat Data Jurusan
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning">
              <div class="inner">
                <h3><?= $jumlah_agama; ?></h3>
                <p>Total Agama</p>
              </div>
              <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                <path d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"/>
              </svg>
              <a href="dataagama.php" class="small-box-footer text-dark">
                <i class="bi bi-arrow-right-circle"></i> Lihat Data Agama
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box text-bg-danger">
              <div class="inner">
                <h3><i class="bi bi-person-circle"></i></h3>
                <p>Profile Pengguna</p>
              </div>
              <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
              <a href="profile.php" class="small-box-footer">
                <i class="bi bi-arrow-right-circle"></i> Lihat Profile
              </a>
            </div>
          </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
          <!-- Pie Chart - Gender Distribution -->
          <div class="col-md-4">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title mb-0">
                  <i class="bi bi-pie-chart-fill me-2"></i>
                  Data Gender
                </h4>
              </div>
              <div class="card-body">
                <div class="chart-container">
                  <canvas id="genderChart"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- Bar Chart - Jurusan Distribution -->
          <div class="col-md-4">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title mb-0">
                  <i class="bi bi-bar-chart-fill me-2"></i>
                  Data Jurusan
                </h4>
              </div>
              <div class="card-body">
                <div class="chart-container">
                  <canvas id="jurusanChart"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- Bar Chart - Agama Distribution -->
          <div class="col-md-4">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title mb-0">
                  <i class="bi bi-bar-chart-steps me-2"></i>
                  Data Agama
                </h4>
              </div>
              <div class="card-body">
                <div class="chart-container">
                  <canvas id="agamaChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

     

<footer class="main-footer text-center text-sm">
  <strong>
    Copyright &copy; 2024
    <a href="https://smkn6solo.sch.id/" class="text-decoration-none">SMK Negeri 6 Surakarta</a>.
    All rights reserved.
  </strong> 
  <div class="float-end d-none d-sm-inline">
    <b>Version</b> 2.0.0
  </div>
</footer> 
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.js"></script>

<script>
// Data untuk grafik
const genderData = <?= json_encode($data_gender) ?>;
const jurusanData = <?= json_encode($data_jurusan) ?>;
const agamaData = <?= json_encode($data_agama) ?>;

// Konfigurasi warna yang konsisten
const colors = {
    primary: ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
    gradient: ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe']
};

// 1. PIE CHART - Gender Distribution
const genderLabels = genderData.map(item => item.jeniskelamin === 'L' ? 'Laki-laki' : 'Perempuan');
const genderValues = genderData.map(item => parseInt(item.jumlah));

const genderCtx = document.getElementById('genderChart').getContext('2d');
const genderChart = new Chart(genderCtx, {
    type: 'pie',
    data: {
        labels: genderLabels,
        datasets: [{
            data: genderValues,
            backgroundColor: colors.primary.slice(0, 2),
            borderColor: '#ffffff',
            borderWidth: 3,
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    font: {
                        size: 12,
                        weight: 'bold'
                    }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return `${label}: ${value} siswa (${percentage}%)`;
                    }
                }
            }
        },
        animation: {
            animateRotate: true,
            animateScale: true
        }
    }
});

// 2. BAR CHART - Jurusan Distribution
const jurusanLabels = jurusanData.map(item => item.namajurusan);
const jurusanValues = jurusanData.map(item => parseInt(item.jumlah));

const jurusanCtx = document.getElementById('jurusanChart').getContext('2d');
const jurusanChart = new Chart(jurusanCtx, {
    type: 'bar',
    data: {
        labels: jurusanLabels,
        datasets: [{
            label: 'Jumlah Siswa',
            data: jurusanValues,
            backgroundColor: colors.gradient.slice(0, jurusanLabels.length),
            borderColor: colors.gradient.slice(0, jurusanLabels.length),
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.dataset.label}: ${context.parsed.y} siswa`;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    font: {
                        size: 11
                    }
                },
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                }
            },
            x: {
                ticks: {
                    maxRotation: 45,
                    font: {
                        size: 10
                    }
                },
                grid: {
                    display: false
                }
            }
        },
        animation: {
            duration: 2000,
            easing: 'easeInOutQuart'
        }
    }
});

// 3. BAR CHART - Agama Distribution
const agamaLabels = agamaData.map(item => item.namaagama);
const agamaValues = agamaData.map(item => parseInt(item.jumlah));

const agamaCtx = document.getElementById('agamaChart').getContext('2d');
const agamaChart = new Chart(agamaCtx, {
    type: 'bar',
    data: {
        labels: agamaLabels,
        datasets: [{
            label: 'Jumlah Siswa',
            data: agamaValues,
            backgroundColor: colors.primary.slice(0, agamaLabels.length),
            borderColor: colors.primary.slice(0, agamaLabels.length),
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.dataset.label}: ${context.parsed.y} siswa`;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    font: {
                        size: 11
                    }
                },
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                }
            },
            x: {
                ticks: {
                    maxRotation: 45,
                    font: {
                        size: 10
                    }
                },
                grid: {
                    display: false
                }
            }
        },
        animation: {
            duration: 2000,
            easing: 'easeInOutQuart'
        }
    }
});

// Dropdown functionality
$(document).ready(function() {
    console.log('Dashboard loaded successfully');
    
    // Initialize dropdowns
    $('.dropdown-toggle').each(function() {
        if (!$(this).attr('data-bs-toggle')) {
            $(this).attr('data-bs-toggle', 'dropdown');
        }
    });
    
    // Enhanced dropdown handler
    $('.dropdown-toggle').off('click').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $parent = $(this).parent('.dropdown');
        const $menu = $parent.find('.dropdown-menu');
        
        // Close other dropdowns
        $('.dropdown-menu').not($menu).removeClass('show');
        $('.dropdown-toggle').not(this).attr('aria-expanded', 'false');
        
        // Toggle current dropdown
        if ($menu.hasClass('show')) {
            $menu.removeClass('show');
            $(this).attr('aria-expanded', 'false');
        } else {
            $menu.addClass('show');
            $(this).attr('aria-expanded', 'true');
        }
    });
    
    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').removeClass('show');
            $('.dropdown-toggle').attr('aria-expanded', 'false');
        }
    });
    
    // Prevent dropdown from closing when clicking inside
    $('.dropdown-menu').on('click', function(e) {
        e.stopPropagation();
    });
    
    // Add smooth hover effects to cards
    $('.card').hover(
        function() {
            $(this).css('transform', 'translateY(-2px)');
        },
        function() {
            $(this).css('transform', 'translateY(0)');
        }
    );
});
</script>

</body>
</html>