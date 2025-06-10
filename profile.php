<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$db = new database();
$username = $_SESSION['username'];

// Ambil data user
$query = $db->koneksi->prepare("SELECT username, email, nama_lengkap, role FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();

if ($result->num_rows !== 1) {
    echo "User tidak ditemukan.";
    exit;
}

$user = $result->fetch_assoc();

// Proses update profil
$updateMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $namaLengkap = $_POST['nama_lengkap'] ?? '';
    $email = $_POST['email'] ?? '';

    $stmt = $db->koneksi->prepare("UPDATE users SET nama_lengkap = ?, email = ? WHERE username = ?");
    $stmt->bind_param("sss", $namaLengkap, $email, $username);
    if ($stmt->execute()) {
        $updateMessage = '<div class="alert alert-success">Profil berhasil diperbarui.</div>';
        // Refresh data
        $user['nama_lengkap'] = $namaLengkap;
        $user['email'] = $email;
    } else {
        $updateMessage = '<div class="alert alert-danger">Gagal memperbarui profil.</div>';
    }
    $stmt->close();
}

// Proses update password
$passwordMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Ambil password hash dari DB
    $passQuery = $db->koneksi->prepare("SELECT password FROM users WHERE username = ?");
    $passQuery->bind_param("s", $username);
    $passQuery->execute();
    $passResult = $passQuery->get_result();
    $passRow = $passResult->fetch_assoc();
    $currentHash = $passRow['password'];

    if (!password_verify($oldPassword, $currentHash)) {
        $passwordMessage = '<div class="alert alert-danger">Password lama salah.</div>';
    } elseif ($newPassword !== $confirmPassword) {
        $passwordMessage = '<div class="alert alert-danger">Password baru dan konfirmasi tidak cocok.</div>';
    } elseif (strlen($newPassword) < 6) {
        $passwordMessage = '<div class="alert alert-danger">Password baru minimal 6 karakter.</div>';
    } else {
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updatePass = $db->koneksi->prepare("UPDATE users SET password = ? WHERE username = ?");
        $updatePass->bind_param("ss", $newHash, $username);
        if ($updatePass->execute()) {
            $passwordMessage = '<div class="alert alert-success">Password berhasil diperbarui.</div>';
        } else {
            $passwordMessage = '<div class="alert alert-danger">Gagal memperbarui password.</div>';
        }
        $updatePass->close();
    }
    $passQuery->close();
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>AdminLTE v4 | Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.css" />
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
<?php include "navbar.php"; ?>
<?php include "sidebar.php"; ?>
<div class="content-wrapper">
  <main class="app-main">
    <div class="app-content-header py-3">
      <div class="container-fluid">
        <h3 class="mb-0">Profil Pengguna</h3>
      </div>
    </div>

    <div class="app-content">
      <div class="container-fluid">
        <?= $updateMessage ?>
        <?= $passwordMessage ?>
        <div class="card shadow-sm rounded-3">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Akun</h5>
            <div>
              <button class="btn btn-sm btn-outline-light me-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <i class="bi bi-pencil-square"></i> Edit Profil
              </button>
              <button class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                <i class="bi bi-key-fill"></i> Ganti Password
              </button>
            </div>
          </div>
          <div class="card-body">
            <dl class="row mb-0">
              <dt class="col-sm-4">Username</dt>
              <dd class="col-sm-8"><?= htmlspecialchars($user['username']) ?></dd>

              <dt class="col-sm-4">Nama Lengkap</dt>
              <dd class="col-sm-8"><?= htmlspecialchars($user['nama_lengkap'] ?: '-') ?></dd>

              <dt class="col-sm-4">Email</dt>
              <dd class="col-sm-8"><?= htmlspecialchars($user['email'] ?: '-') ?></dd>

              <dt class="col-sm-4">Role</dt>
              <dd class="col-sm-8"><?= htmlspecialchars($user['role'] ?: '-') ?></dd>

            </dl>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>

<!-- Modal Edit Profil -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content needs-validation" novalidate>
      <input type="hidden" name="update_profile" value="1" />
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileLabel">Edit Profil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="namaLengkap" class="form-label">Nama Lengkap</label>
          <input
            type="text"
            class="form-control"
            id="namaLengkap"
            name="nama_lengkap"
            value="<?= htmlspecialchars($user['nama_lengkap']) ?>"
            required
          />
          <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
        </div>
        <div class="mb-3">
          <label for="emailUser" class="form-label">Email</label>
          <input
            type="email"
            class="form-control"
            id="emailUser"
            name="email"
            value="<?= htmlspecialchars($user['email']) ?>"
            required
          />
          <div class="invalid-feedback">Email tidak valid.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Password -->
<div class="modal fade" id="editPasswordModal" tabindex="-1" aria-labelledby="editPasswordLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content needs-validation" novalidate>
      <input type="hidden" name="update_password" value="1" />
      <div class="modal-header">
        <h5 class="modal-title" id="editPasswordLabel">Ganti Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="oldPassword" class="form-label">Password Lama</label>
          <input
            type="password"
            class="form-control"
            id="oldPassword"
            name="old_password"
            required
          />
          <div class="invalid-feedback">Masukkan password lama.</div>
        </div>
        <div class="mb-3">
          <label for="newPassword" class="form-label">Password Baru</label>
          <input
            type="password"
            class="form-control"
            id="newPassword"
            name="new_password"
            minlength="6"
            required
          />
          <div class="invalid-feedback">Password baru minimal 6 karakter.</div>
        </div>
        <div class="mb-3">
          <label for="confirmPassword" class="form-label">Konfirmasi Password Baru</label>
          <input
            type="password"
            class="form-control"
            id="confirmPassword"
            name="confirm_password"
            minlength="6"
            required
          />
          <div class="invalid-feedback">Konfirmasi password harus sama.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Ganti Password</button>
      </div>
    </form>
  </div>
</div>

<?php include "footer.php"; ?>

<script src="dist/js/adminlte.js"></script>
<script>
// Bootstrap 5 form validation (client-side)
(() => {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation')
  forms.forEach(form => {
    form.addEventListener('submit', event => {
      // Password konfirmasi harus cocok manual
      if (form.id === 'editPasswordModal') {
        const newPass = form.querySelector('input[name="new_password"]').value;
        const confirmPass = form.querySelector('input[name="confirm_password"]').value;
        if (newPass !== confirmPass) {
          event.preventDefault()
          event.stopPropagation()
          alert('Password baru dan konfirmasi tidak cocok.')
          return
        }
      }
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})();
</script>
</body>
</html>
