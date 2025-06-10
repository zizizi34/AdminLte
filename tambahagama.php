<?php
include "koneksi.php";
$db = new database();

$error = '';
$success = '';

if (isset($_POST['simpan'])) { 
    // Sanitasi dan validasi input
    $kodeagama = isset($_POST['kodeagama']) ? trim($_POST['kodeagama']) : '';
    $namaagama = isset($_POST['namaagama']) ? trim($_POST['namaagama']) : '';

    // Array untuk menyimpan semua error
    $errors = [];

    // Validasi Kode Agama
    if (empty($kodeagama)) {
        $errors[] = "Kode Agama harus diisi.";
    } else {
        // Validasi format - hanya angka
        if (!preg_match('/^[0-9]+$/', $kodeagama)) {
            $errors[] = "Kode Agama hanya boleh berisi angka.";
        }
        // Validasi range angka (1-999999)
        $kodeInt = intval($kodeagama);
        if ($kodeInt < 1 || $kodeInt > 999999) {
            $errors[] = "Kode Agama harus antara 1 sampai 999999.";
        }
        // Validasi tidak boleh leading zero (kecuali angka 0)
        if (strlen($kodeagama) > 1 && $kodeagama[0] === '0') {
            $errors[] = "Kode Agama tidak boleh dimulai dengan angka 0.";
        }
        // Cek duplikasi kode (asumsi ada method untuk cek)
        // Uncomment jika ada method check_duplicate_kode di class database
        // if ($db->check_duplicate_kode($kodeagama)) {
        //     $errors[] = "Kode Agama sudah digunakan. Silakan gunakan kode lain.";
        // }
    }

    // Validasi Nama Agama
    if (empty($namaagama)) {
        $errors[] = "Nama Agama harus diisi.";
    } else {
        // Validasi panjang nama
        if (strlen($namaagama) < 3 || strlen($namaagama) > 50) {
            $errors[] = "Nama Agama harus antara 3-50 karakter.";
        }
        // Validasi format - hanya huruf, spasi, dan beberapa karakter khusus yang diizinkan
        if (!preg_match('/^[A-Za-z\s\-\'\.]+$/', $namaagama)) {
            $errors[] = "Nama Agama hanya boleh berisi huruf, spasi, tanda hubung (-), apostrof ('), dan titik (.).";
        }
        // Validasi tidak boleh dimulai atau diakhiri dengan spasi
        if ($namaagama !== trim($namaagama)) {
            $errors[] = "Nama Agama tidak boleh dimulai atau diakhiri dengan spasi.";
        }
        // Validasi tidak boleh ada spasi ganda
        if (preg_match('/\s{2,}/', $namaagama)) {
            $errors[] = "Nama Agama tidak boleh mengandung spasi ganda.";
        }
        // Validasi tidak boleh hanya spasi
        if (trim($namaagama) === '') {
            $errors[] = "Nama Agama tidak boleh hanya berisi spasi.";
        }
        // Cek duplikasi nama (asumsi ada method untuk cek)
        // Uncomment jika ada method check_duplicate_nama di class database
        // if ($db->check_duplicate_nama($namaagama)) {
        //     $errors[] = "Nama Agama sudah ada. Silakan gunakan nama lain.";
        // }
    }

    // Validasi CSRF Token (opsional tapi direkomendasikan)
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        $errors[] = "Token keamanan tidak valid. Silakan refresh halaman dan coba lagi.";
    }

    // Jika ada error, gabungkan menjadi satu string
    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    } else {
        // Sanitasi tambahan sebelum menyimpan ke database
        $kodeagama = htmlspecialchars($kodeagama, ENT_QUOTES, 'UTF-8');
        $namaagama = htmlspecialchars($namaagama, ENT_QUOTES, 'UTF-8');
        
        try {
            $result = $db->tambah_agama($kodeagama, $namaagama);
            if ($result) {
                $success = "Data agama berhasil ditambahkan.";
                // Redirect setelah berhasil untuk mencegah duplicate submission
                header("Location: dataagama.php?success=1");
                exit();
            } else {
                $error = "Gagal menyimpan data. Silakan coba lagi.";
            }
        } catch (Exception $e) {
            $error = "Terjadi kesalahan sistem. Silakan coba lagi.";
            // Log error untuk debugging (jangan tampilkan ke user)
            error_log("Database error: " . $e->getMessage());
        }
    }
}

// Generate CSRF Token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Tambah Agama</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.css" />
  <style>
    .invalid-feedback {
      display: block;
    }
    .form-control.is-invalid {
      border-color: #dc3545;
    }
    .character-count {
      font-size: 0.875rem;
      color: #6c757d;
    }
    .character-count.warning {
      color: #ffc107;
    }
    .character-count.danger {
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
              <div class="col-sm-6"><h3 class="mb-0">Tambah Agama</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="dashboard.html">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Tambah Data Agama</li>
                </ol>
              </div>
              
            
            <div class="row">
              <div class="col-md-8">
                <div class="card card-info card-outline mb-4">
                  <!--begin::Header-->
                  <div class="card-header">
                    <div class="card-title">Formulir Data Agama</div>
                  </div>
                  <!--end::Header-->  
                  <!--begin::Form-->
                  <form action="" method="POST" class="needs-validation" novalidate id="agamaForm">
                    <!--begin::Body-->
                    <div class="card-body">
                      <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                          <i class="bi bi-exclamation-triangle-fill me-2"></i>
                          <?= $error ?>
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                      <?php endif; ?>
                      
                      <?php if (!empty($success)) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                          <i class="bi bi-check-circle-fill me-2"></i>
                          <?= htmlspecialchars($success) ?>
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                      <?php endif; ?>

                      <!-- CSRF Token -->
                      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                      
                      <div class="mb-3">
                        <label for="kodeagama" class="form-label">
                          Kode Agama <span class="text-danger">*</span>
                        </label>
                        <input 
                          type="number" 
                          class="form-control" 
                          id="kodeagama" 
                          name="kodeagama" 
                          required 
                          min="1"
                          max="999999"
                          step="1"
                          autocomplete="off"
                          value="<?= isset($_POST['kodeagama']) ? htmlspecialchars($_POST['kodeagama']) : '' ?>" 
                        />
                        <div class="invalid-feedback">
                          Kode Agama harus diisi dengan angka antara 1-999999.
                        </div>
                        <div class="character-count mt-1" id="kodeCount">Range: 1 - 999999</div>
                      </div>  
                      
                      <div class="mb-3">
                        <label for="namaagama" class="form-label">
                          Nama Agama <span class="text-danger">*</span>
                        </label>
                        <input 
                          type="text" 
                          class="form-control" 
                          id="namaagama" 
                          name="namaagama" 
                          required 
                          maxlength="50"
                          minlength="3"
                          pattern="[A-Za-z\s\-\'\.]+"
                          autocomplete="off"
                          value="<?= isset($_POST['namaagama']) ? htmlspecialchars($_POST['namaagama']) : '' ?>" 
                        />
                        <div class="invalid-feedback">
                          Nama Agama harus diisi (3-50 karakter, hanya huruf, spasi, tanda hubung, apostrof, dan titik).
                        </div>
                        <div class="character-count mt-1" id="namaCount">0/50 karakter</div>
                      </div>
                    </div>
                    <!--end::Body-->
                    
                    <!--begin::Footer-->
                    <div class="card-footer">
                      <button type="submit" name="simpan" class="btn btn-primary me-2" id="submitBtn">
                        <i class="bi bi-save me-1"></i>Tambah Agama
                      </button>
                      <a href="dataagama.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                      </a>
                    </div>
                    <!--end::Footer-->
                  </form>
                  <!--end::Form-->
                </div>
              </div>
              
              <!-- Info Panel -->
              
                </div>
              </div>
            </div>
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
  
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
        <?php include "footer.php"; ?>
    <!--begin::Script-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="dist/js/adminlte.js"></script>
    
    <!--begin::Custom Validation Script-->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('agamaForm');
        const kodeInput = document.getElementById('kodeagama');
        const namaInput = document.getElementById('namaagama');
        const kodeCount = document.getElementById('kodeCount');
        const namaCount = document.getElementById('namaCount');
        const submitBtn = document.getElementById('submitBtn');

        // Character counter function
        function updateCharacterCount(input, counter, maxLength) {
          const currentLength = input.value.length;
          counter.textContent = `${currentLength}/${maxLength} karakter`;
          
          if (currentLength > maxLength * 0.8) {
            counter.classList.add('warning');
            counter.classList.remove('danger');
          } else if (currentLength >= maxLength) {
            counter.classList.add('danger');
            counter.classList.remove('warning');
          } else {
            counter.classList.remove('warning', 'danger');
          }
        }

        // Real-time validation function
        function validateInput(input, isNumber = false, minValue = 0, maxValue = Infinity, minLength = 0, maxLength = Infinity, pattern = null) {
          const value = input.value.trim();
          let isValid = true;
          let errorMsg = '';

          if (value.length === 0) {
            isValid = false;
            errorMsg = 'Field ini harus diisi';
          } else if (isNumber) {
            const numValue = parseInt(value);
            if (isNaN(numValue)) {
              isValid = false;
              errorMsg = 'Harus berupa angka';
            } else if (numValue < minValue || numValue > maxValue) {
              isValid = false;
              errorMsg = `Angka harus antara ${minValue}-${maxValue}`;
            } else if (value.length > 1 && value[0] === '0') {
              isValid = false;
              errorMsg = 'Tidak boleh dimulai dengan 0';
            }
          } else if (value.length < minLength) {
            isValid = false;
            errorMsg = `Minimal ${minLength} karakter`;
          } else if (value.length > maxLength) {
            isValid = false;
            errorMsg = `Maksimal ${maxLength} karakter`;
          } else if (pattern && !pattern.test(value)) {
            isValid = false;
            errorMsg = 'Format tidak sesuai';
          }

          // Update UI
          if (isValid) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
          } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
              feedback.textContent = errorMsg;
            }
          }

          return isValid;
        }

        // Event listeners for validation
        kodeInput.addEventListener('input', function() {
          const value = this.value;
          kodeCount.textContent = value ? `Nilai: ${value}` : 'Range: 1 - 999999';
          validateInput(this, true, 1, 999999);
        });

        namaInput.addEventListener('input', function() {
          updateCharacterCount(this, namaCount, 50);
          validateInput(this, false, 0, Infinity, 3, 50, /^[A-Za-z\s\-\'\.]+$/);
        });

        // Real-time validation for blur events
        kodeInput.addEventListener('blur', function() {
          const value = this.value.trim();
          
          // Additional validations for number input
          if (value) {
            const numValue = parseInt(value);
            if (isNaN(numValue) || numValue < 1 || numValue > 999999) {
              this.classList.add('is-invalid');
              const feedback = this.nextElementSibling;
              if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = 'Kode harus berupa angka antara 1-999999';
              }
            } else if (value.length > 1 && value[0] === '0') {
              this.classList.add('is-invalid');
              const feedback = this.nextElementSibling;
              if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = 'Kode tidak boleh dimulai dengan angka 0';
              }
            }
          }
        });

        namaInput.addEventListener('blur', function() {
          const value = this.value;
          
          // Additional validations
          if (value !== value.trim()) {
            this.classList.add('is-invalid');
            const feedback = this.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
              feedback.textContent = 'Tidak boleh dimulai atau diakhiri dengan spasi';
            }
          } else if (/\s{2,}/.test(value)) {
            this.classList.add('is-invalid');
            const feedback = this.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
              feedback.textContent = 'Tidak boleh ada spasi ganda';
            }
          }
        });

        // Form submission validation
        form.addEventListener('submit', function(event) {
          event.preventDefault();
          event.stopPropagation();

          const kodeValid = validateInput(kodeInput, true, 1, 999999);
          const namaValid = validateInput(namaInput, false, 0, Infinity, 3, 50, /^[A-Za-z\s\-\'\.]+$/);

          // Additional checks
          const kodeValue = kodeInput.value.trim();
          const namaValue = namaInput.value.trim();

          let allValid = kodeValid && namaValid;

          // Additional validation for kode
          if (kodeValue) {
            const numValue = parseInt(kodeValue);
            if (isNaN(numValue) || numValue < 1 || numValue > 999999) {
              allValid = false;
              kodeInput.classList.add('is-invalid');
            } else if (kodeValue.length > 1 && kodeValue[0] === '0') {
              allValid = false;
              kodeInput.classList.add('is-invalid');
            }
          }

          // Additional validation for nama
          if (namaValue !== namaInput.value || /\s{2,}/.test(namaValue)) {
            allValid = false;
            namaInput.classList.add('is-invalid');
          }

          form.classList.add('was-validated');

          if (allValid) {
            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Menyimpan...';
            
            // Submit form
            this.submit();
          }
        });

        // Initialize displays
        const kodeValue = kodeInput.value;
        kodeCount.textContent = kodeValue ? `Nilai: ${kodeValue}` : 'Range: 1 - 999999';
        updateCharacterCount(namaInput, namaCount, 50);

        // Prevent invalid input on kode field
        kodeInput.addEventListener('keypress', function(e) {
          // Allow only numbers, backspace, delete, tab, escape, enter
          if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
              // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
              (e.keyCode === 65 && e.ctrlKey === true) ||
              (e.keyCode === 67 && e.ctrlKey === true) ||
              (e.keyCode === 86 && e.ctrlKey === true) ||
              (e.keyCode === 88 && e.ctrlKey === true)) {
            return;
          }
          // Ensure it's a number and stop keypress if not
          if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
          }
        });

        // Prevent paste of invalid characters for kode
        kodeInput.addEventListener('paste', function(e) {
          setTimeout(() => {
            this.value = this.value.replace(/[^0-9]/g, '');
            const kodeValue = this.value;
            kodeCount.textContent = kodeValue ? `Nilai: ${kodeValue}` : 'Range: 1 - 999999';
            validateInput(this, true, 1, 999999);
          }, 0);
        });

        // Prevent paste of invalid characters for nama
        namaInput.addEventListener('paste', function(e) {
          setTimeout(() => {
            this.value = this.value.replace(/[^A-Za-z\s\-\'\.]/g, '');
            updateCharacterCount(this, namaCount, 50);
            validateInput(this, false, 0, Infinity, 3, 50, /^[A-Za-z\s\-\'\.]+$/);
          }, 0);
        });
      });

      // OverlayScrollbars configuration
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
    <!--end::Custom Validation Script-->
    <!--end::Script-->
  </body>
</html>