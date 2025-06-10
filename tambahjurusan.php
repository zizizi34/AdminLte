<?php
include "koneksi.php";
$db = new database();

$error = '';
$success = '';

if (isset($_POST['simpan'])) { 
    $kodejurusan = trim($_POST['kodejurusan']);
    $namajurusan = trim($_POST['namajurusan']);
    
    // Array untuk menyimpan error
    $errors = array();

    // Validasi Kode Jurusan yang lebih ketat
    if (empty($kodejurusan)) {
        $errors[] = "Kode Jurusan wajib diisi.";
    } elseif (!is_numeric($kodejurusan)) {
        $errors[] = "Kode Jurusan harus berupa angka.";
    } elseif ($kodejurusan < 1 || $kodejurusan > 20) {
        $errors[] = "Kode Jurusan harus antara 1 sampai 20.";
    } elseif (floor($kodejurusan) != $kodejurusan) {
        $errors[] = "Kode Jurusan harus berupa bilangan bulat.";
    } else {
        // Cek duplikasi kode jurusan (jika ada method di database class)
        // if ($db->cek_kode_jurusan_exists($kodejurusan)) {
        //     $errors[] = "Kode Jurusan sudah digunakan. Pilih kode yang lain.";
        // }
    }

    // Validasi Nama Jurusan yang lebih ketat
    if (empty($namajurusan)) {
        $errors[] = "Nama Jurusan wajib diisi.";
    } elseif (strlen($namajurusan) < 3) {
        $errors[] = "Nama Jurusan minimal 3 karakter.";
    } elseif (strlen($namajurusan) > 50) {
        $errors[] = "Nama Jurusan maksimal 50 karakter.";
    } elseif (!preg_match('/^[A-Za-z\s]+$/', $namajurusan)) {
        $errors[] = "Nama Jurusan hanya boleh berisi huruf dan spasi.";
    } elseif (preg_match('/^\s+|\s+$/', $namajurusan)) {
        $errors[] = "Nama Jurusan tidak boleh diawali atau diakhiri dengan spasi.";
    } elseif (preg_match('/\s{2,}/', $namajurusan)) {
        $errors[] = "Nama Jurusan tidak boleh mengandung spasi berurutan.";
    } else {
        // Cek duplikasi nama jurusan (jika ada method di database class)
        // if ($db->cek_nama_jurusan_exists($namajurusan)) {
        //     $errors[] = "Nama Jurusan sudah ada. Gunakan nama yang berbeda.";
        // }
    }

    // Jika tidak ada error, simpan data
    if (empty($errors)) {
        try {
            $db->tambah_jurusan($kodejurusan, $namajurusan);
            $success = "Data jurusan berhasil ditambahkan!";
            // Redirect setelah 2 detik untuk menampilkan pesan sukses
            header("refresh:2;url=datajurusan.php");
        } catch (Exception $e) {
            $errors[] = "Gagal menyimpan data: " . $e->getMessage();
        }
    }
    
    // Gabungkan semua error menjadi satu string
    if (!empty($errors)) {
        $error = implode("<br>", $errors);
    }
}

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Tambah Jurusan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Form Tambah Data Jurusan" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.css" />
  <style>
    .validation-info {
      font-size: 0.875rem;
      color: #6c757d;
      margin-top: 0.25rem;
    }
    .form-control.is-invalid {
      border-color: #dc3545;
    }
    .form-control.is-valid {
      border-color: #198754;
    }
    .required-field::after {
      content: " *";
      color: #dc3545;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
<?php include "navbar.php"; ?>
<?php include "sidebar.php"; ?>
<div class="content-wrapper">
  <main class="app-main">
        
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Form Tambah Data Jurusan</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Form Tambah Data Jurusan</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        
        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row g-4">
              <div class="col-md-8">
                
                <!-- Panduan Pengisian Form -->
               

                <!--begin::Horizontal Form-->
                <div class="card card-warning card-outline mb-4">
                  <!--begin::Header-->
                  <div class="card-header">
                    <div class="card-title">Tambah Data Jurusan</div>
                  </div>
                  <!--end::Header-->
                  
                  <!--begin::Form-->
                  <form action="" method="POST" class="needs-validation" novalidate id="formJurusan">
                    <!--begin::Body-->
                    <div class="card-body">
                      
                      <!-- Alert Error -->
                      <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                          <i class="bi bi-exclamation-triangle-fill"></i>
                          <strong>Error!</strong><br>
                          <?= $error ?>
                          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                      <?php endif; ?>
                      
                      <!-- Alert Success -->
                      <?php if (!empty($success)) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                          <i class="bi bi-check-circle-fill"></i>
                          <strong>Berhasil!</strong><br>
                          <?= $success ?> Anda akan diarahkan ke halaman data jurusan...
                          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                      <?php endif; ?>
                      
                      <!-- Kode Jurusan -->
                      <div class="mb-3">
                        <label for="kodejurusan" class="form-label required-field">Kode Jurusan</label>
                        <input type="number" 
                               class="form-control" 
                               id="kodejurusan" 
                               name="kodejurusan" 
                               min="1"
                               max="20"
                               step="1"
                               value="<?= isset($_POST['kodejurusan']) ? htmlspecialchars($_POST['kodejurusan']) : '' ?>"
                               placeholder="Masukkan kode jurusan (1-20)"
                               required />
                        <div class="validation-info">
                          <i class="bi bi-info-circle"></i> Masukkan angka antara 1 sampai 20
                        </div>
                        <div class="invalid-feedback">
                          Kode jurusan harus berupa angka antara 1-20
                        </div>
                      </div>  
                      
                      <!-- Nama Jurusan -->
                      <div class="mb-3">
                        <label for="namajurusan" class="form-label required-field">Nama Jurusan</label>
                        <input type="text" 
                               class="form-control" 
                               id="namajurusan" 
                               name="namajurusan" 
                               minlength="3"
                               maxlength="50"
                               value="<?= isset($_POST['namajurusan']) ? htmlspecialchars($_POST['namajurusan']) : '' ?>"
                               placeholder="Masukkan nama jurusan"
                               required />
                        <div class="validation-info">
                          <i class="bi bi-info-circle"></i> Minimal 3 karakter, maksimal 50 karakter. Hanya huruf dan spasi.
                        </div>
                        <div class="invalid-feedback">
                          Nama jurusan harus 3-50 karakter, hanya huruf dan spasi
                        </div>
                      </div>
                      
                    </div>
                    <!--end::Body-->
                    
                    <!--begin::Footer-->
                    <div class="card-footer">
                      <button type="submit" name="simpan" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Jurusan
                      </button>
                      <a href="datajurusan.php" class="btn btn-secondary ms-2">
                        <i class="bi bi-arrow-left"></i> Kembali
                      </a>
                    </div>
                    <!--end::Footer-->
                  </form>
                  <!--end::Form-->
                </div>
                <!--end::Horizontal Form-->
              </div>
              <!--end::Col-->
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
    </div>
    <!--end::App Wrapper-->
    
    <?php include "footer.php"; ?>
    
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    
    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->
    
    <!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)-->
    
    <!--begin::Required Plugin(AdminLTE)-->
    <script src="../../../dist/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)-->
    
    <!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->
    
    <!--begin::Custom Validation Script-->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formJurusan');
        const kodeJurusan = document.getElementById('kodejurusan');
        const namaJurusan = document.getElementById('namajurusan');
        
        // Real-time validation untuk Kode Jurusan
        kodeJurusan.addEventListener('input', function() {
          const value = this.value;
          const isValid = value !== '' && 
                         !isNaN(value) && 
                         parseInt(value) >= 1 && 
                         parseInt(value) <= 20 && 
                         parseInt(value) == parseFloat(value);
          
          if (isValid) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
          } else {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
          }
        });
        
        // Real-time validation untuk Nama Jurusan
        namaJurusan.addEventListener('input', function() {
          const value = this.value.trim();
          const isValid = value.length >= 3 && 
                         value.length <= 50 &&
                         /^[A-Za-z\s]+$/.test(value) &&
                         !/^\s+|\s+$/.test(value) &&
                         !/\s{2,}/.test(value);
          
          if (isValid) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
          } else {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
          }
        });
        
        // Form submission validation
        form.addEventListener('submit', function(event) {
          event.preventDefault();
          event.stopPropagation();
          
          let isFormValid = true;
          
          // Validasi Kode Jurusan
          const kodeValue = kodeJurusan.value.trim();
          if (!kodeValue || isNaN(kodeValue) || parseInt(kodeValue) < 1 || parseInt(kodeValue) > 20 || parseInt(kodeValue) != parseFloat(kodeValue)) {
            kodeJurusan.classList.add('is-invalid');
            isFormValid = false;
          } else {
            kodeJurusan.classList.remove('is-invalid');
            kodeJurusan.classList.add('is-valid');
          }
          
          // Validasi Nama Jurusan
          const namaValue = namaJurusan.value.trim();
          if (!namaValue || namaValue.length < 3 || namaValue.length > 50 || 
              !/^[A-Za-z\s]+$/.test(namaValue) || /^\s+|\s+$/.test(namaValue) || 
              /\s{2,}/.test(namaValue)) {
            namaJurusan.classList.add('is-invalid');
            isFormValid = false;
          } else {
            namaJurusan.classList.remove('is-invalid');
            namaJurusan.classList.add('is-valid');
          }
          
          form.classList.add('was-validated');
          
          if (isFormValid) {
            // Submit form jika valid
            this.submit();
          }
        });
      });
    </script>
    <!--end::Custom Validation Script-->
    
  </body>
</html>