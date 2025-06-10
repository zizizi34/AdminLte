<?php
session_start();
include 'koneksi.php';
$db = new database;
$conn = $db->koneksi;
$error = '';
$success = '';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = isset($_POST['role']) ? $_POST['role'] : 'siswa'; // default role siswa

    // Validasi input
    if (empty($nama_lengkap) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Semua field harus diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok!";
    } elseif ($role !== 'siswa' && $role !== 'admin') {
        $error = "Role tidak valid!";
    } else {
        // Cek username atau email sudah terdaftar
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Username atau email sudah terdaftar!";
        } else {
            // Hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert user baru dengan role
            $stmt = $conn->prepare("INSERT INTO users (nama_lengkap, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nama_lengkap, $username, $email, $password_hash, $role);
            if ($stmt->execute()) {
                $success = "Registrasi berhasil! Silakan login dengan akun Anda.";
                // Redirect ke login dengan pesan sukses
                header("Location: register.php?registered=success");
                exit();
            } else {
                $error = "Terjadi kesalahan saat registrasi. Silakan coba lagi.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Register - SMK Negeri 6 Surakarta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="Register - SMK Negeri 6 Surakarta" />
    <meta name="author" content="SMK Negeri 6 Surakarta" />
    <meta name="description" content="Halaman registrasi website data SMK Negeri 6 Surakarta" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="../../../dist/css/adminlte.css" />
  </head>
  <body class="register-page bg-body-secondary">
    <div class="register-box">
      <div class="card card-outline card-primary">
        <div class="card-header text-center">
          <h1><b>SMK Negeri 6</b> Surakarta</h1>
        </div>
        <div class="card-body register-card-body">
          <p class="login-box-msg">Daftar Akun Baru<br>Website Data SMK Negeri 6 Surakarta</p>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              <strong>Error!</strong> <?php echo htmlspecialchars($error); ?>
            </div>
          <?php endif; ?>

          <?php if (isset($_GET['registered']) && $_GET['registered'] == 'success'): ?>
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              <strong>Berhasil!</strong> Registrasi berhasil! Silakan login dengan akun Anda.
            </div>
          <?php endif; ?>

          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="registerForm">
            <div class="input-group mb-3">
              <div class="form-floating">
                <input
                  name="nama_lengkap"
                  id="nama_lengkap"
                  type="text"
                  class="form-control"
                  required
                  placeholder="Nama Lengkap"
                  value="<?php echo isset($_POST['nama_lengkap']) ? htmlspecialchars($_POST['nama_lengkap']) : ''; ?>"
                />
                <label for="nama_lengkap">Nama Lengkap</label>
              </div>
              <div class="input-group-text"><span class="bi bi-person"></span></div>
            </div>

            <div class="input-group mb-3">
              <div class="form-floating">
                <input
                  name="username"
                  id="username"
                  type="text"
                  class="form-control"
                  required
                  placeholder="Username"
                  value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                />
                <label for="username">Username</label>
              </div>
              <div class="input-group-text"><span class="bi bi-person-circle"></span></div>
            </div>

            <div class="input-group mb-3">
              <div class="form-floating">
                <input
                  name="email"
                  id="email"
                  type="email"
                  class="form-control"
                  required
                  placeholder="Email"
                  value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                />
                <label for="email">Email</label>
              </div>
              <div class="input-group-text"><span class="bi bi-envelope"></span></div>
            </div>

            <div class="input-group mb-3">
              <div class="form-floating">
                <select name="role" id="role" class="form-select" required>
                  <option value="siswa" <?php echo (isset($_POST['role']) && $_POST['role'] === 'siswa') ? 'selected' : ''; ?>>Siswa</option>
                  <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
                <label for="role">Role</label>
              </div>
              <div class="input-group-text"><span class="bi bi-person-badge"></span></div>
            </div>

            <div class="input-group mb-3">
              <div class="form-floating">
                <input
                  name="password"
                  id="password"
                  type="password"
                  class="form-control"
                  required
                  placeholder="Password"
                />
                <label for="password">Password</label>
              </div>
              <button type="button" class="input-group-text bg-white border-start-0" onclick="togglePassword()" style="cursor: pointer;">
                <i class="bi bi-eye-slash" id="toggleIcon"></i>
              </button>
            </div>

            <div class="input-group mb-3">
              <div class="form-floating">
                <input
                  name="confirm_password"
                  id="confirm_password"
                  type="password"
                  class="form-control"
                  required
                  placeholder="Konfirmasi Password"
                />
                <label for="confirm_password">Konfirmasi Password</label>
              </div>
              <button type="button" class="input-group-text bg-white border-start-0" onclick="toggleConfirmPassword()" style="cursor: pointer;">
                <i class="bi bi-eye-slash" id="toggleConfirmIcon"></i>
              </button>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="d-grid gap-2">
<button type="submit" class="btn btn-primary">Daftar</button>
                </div>
              </div>
            </div>
          </form>

          <p class="mt-3 mb-1 text-center">
            <a href="index.php" class="text-center">Sudah punya akun? Masuk di sini</a>
          </p>
        </div>
      </div>
    </div>

    <script>
      function togglePassword() {
        const passwordInput = document.getElementById("password");
        const toggleIcon = document.getElementById("toggleIcon");

        if (passwordInput.type === "password") {
          passwordInput.type = "text";
          toggleIcon.classList.remove("bi-eye-slash");
          toggleIcon.classList.add("bi-eye");
        } else {
          passwordInput.type = "password";
          toggleIcon.classList.remove("bi-eye");
          toggleIcon.classList.add("bi-eye-slash");
        }
      }

      function toggleConfirmPassword() {
        const passwordInput = document.getElementById("confirm_password");
        const toggleIcon = document.getElementById("toggleConfirmIcon");

        if (passwordInput.type === "password") {
          passwordInput.type = "text";
          toggleIcon.classList.remove("bi-eye-slash");
          toggleIcon.classList.add("bi-eye");
        } else {
          passwordInput.type = "password";
          toggleIcon.classList.remove("bi-eye");
          toggleIcon.classList.add("bi-eye-slash");
        }
      }

      // Validasi form sebelum submit
      document.getElementById("registerForm").addEventListener("submit", function(e) {
        const namaLengkap = document.getElementById("nama_lengkap").value.trim();
        const username = document.getElementById("username").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;

        if (!namaLengkap || !username || !email || !password || !confirmPassword) {
          alert("Semua field harus diisi!");
          e.preventDefault();
          return false;
        }

        if (password.length < 6) {
          alert("Password minimal 6 karakter!");
          e.preventDefault();
          return false;
        }

        if (password !== confirmPassword) {
          alert("Password dan konfirmasi password tidak cocok!");
          e.preventDefault();
          return false;
        }
      });

      // Auto focus ke input pertama
      document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('nama_lengkap').focus();
      });

      // Enter key navigation
      document.getElementById('nama_lengkap').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          document.getElementById('username').focus();
        }
      });
      document.getElementById('username').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          document.getElementById('email').focus();
        }
      });
      document.getElementById('email').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          document.getElementById('password').focus();
        }
      });
      document.getElementById('password').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          document.getElementById('confirm_password').focus();
        }
      });
    </script>

    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    ></script>
    <script src="../../../dist/js/adminlte.js"></script>
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
  </body>
</html>
