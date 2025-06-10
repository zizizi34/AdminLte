<?php
session_start();
require_once 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$db = new database();
$username = $_SESSION['username'] ?? '';

// Cek apakah user adalah admin
$roleQuery = $db->koneksi->prepare("SELECT role FROM users WHERE username = ?");
$roleQuery->bind_param("s", $username);
$roleQuery->execute();
$roleResult = $roleQuery->get_result();

if ($roleResult->num_rows !== 1) {
    echo "User tidak ditemukan.";
    exit;
}

$userRole = $roleResult->fetch_assoc()['role'];
$roleQuery->close();

// Hanya admin yang bisa akses halaman ini
if ($userRole !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

// Proses form (Tambah, Edit, Hapus)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            
            // TAMBAH USER BARU
            case 'add':
                $new_username = trim($_POST['username']);
                $nama_lengkap = trim($_POST['nama_lengkap']);
                $email = trim($_POST['email']);
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $role = $_POST['role'];
                
                // Cek apakah username sudah ada
                $checkStmt = $db->koneksi->prepare("SELECT COUNT(*) as count FROM users WHERE username = ?");
                $checkStmt->bind_param("s", $new_username);
                $checkStmt->execute();
                $checkResult = $checkStmt->get_result();
                $count = $checkResult->fetch_assoc()['count'];
                $checkStmt->close();
                
                if ($count > 0) {
                    echo "<script>alert('Username sudah digunakan!'); window.location.reload();</script>";
                } else {
                    // Insert user baru
                    $stmt = $db->koneksi->prepare("INSERT INTO users (username, nama_lengkap, email, password, role) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $new_username, $nama_lengkap, $email, $password, $role);
                    
                    if ($stmt->execute()) {
                        echo "<script>alert('User berhasil ditambahkan!'); window.location.reload();</script>";
                    } else {
                        echo "<script>alert('Error menambah user!');</script>";
                    }
                    $stmt->close();
                }
                break;
                
            // EDIT USER
            case 'edit':
                $edit_username = trim($_POST['username']);
                $nama_lengkap = trim($_POST['nama_lengkap']);
                $email = trim($_POST['email']);
                $role = $_POST['role'];
                
                // Mencegah admin mengubah role dirinya sendiri
                if ($edit_username === $_SESSION['username'] && $role !== 'admin') {
                    echo "<script>alert('Anda tidak dapat mengubah role akun Anda sendiri!');</script>";
                } else {
                    // Update dengan atau tanpa password baru
                    if (!empty($_POST['password'])) {
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $stmt = $db->koneksi->prepare("UPDATE users SET nama_lengkap = ?, email = ?, password = ?, role = ? WHERE username = ?");
                        $stmt->bind_param("sssss", $nama_lengkap, $email, $password, $role, $edit_username);
                    } else {
                        $stmt = $db->koneksi->prepare("UPDATE users SET nama_lengkap = ?, email = ?, role = ? WHERE username = ?");
                        $stmt->bind_param("ssss", $nama_lengkap, $email, $role, $edit_username);
                    }
                    
                    if ($stmt->execute()) {
                        echo "<script>alert('User berhasil diupdate!'); window.location.reload();</script>";
                    } else {
                        echo "<script>alert('Error mengupdate user!');</script>";
                    }
                    $stmt->close();
                }
                break;
                
            // HAPUS USER
            case 'delete':
                $delete_username = trim($_POST['username']);
                
                // Mencegah admin menghapus dirinya sendiri
                if ($delete_username === $_SESSION['username']) {
                    echo "<script>alert('Anda tidak dapat menghapus akun Anda sendiri!');</script>";
                } else {
                    $stmt = $db->koneksi->prepare("DELETE FROM users WHERE username = ?");
                    $stmt->bind_param("s", $delete_username);
                    
                    if ($stmt->execute()) {
                        echo "<script>alert('User berhasil dihapus!'); window.location.reload();</script>";
                    } else {
                        echo "<script>alert('Error menghapus user!');</script>";
                    }
                    $stmt->close();
                }
                break;
        }
    }
}

// Ambil semua data user untuk ditampilkan
$query = $db->koneksi->prepare("SELECT username, nama_lengkap, email, role FROM users ORDER BY username");
$query->execute();
$result = $query->get_result();
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Manajemen Pengguna | SMK Negeri 6 Surakarta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="dist/css/adminlte.css" />
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include "navbar.php"; ?>
        <?php include "sidebar.php"; ?>
        
        <div class="content-wrapper">
            <main class="app-main">
                <!-- Header -->
                <div class="app-content-header py-3">
                    <div class="container-fluid">
                        <h3 class="mb-0">Manajemen Pengguna</h3>
                        <p class="text-muted">Kelola data admin dan siswa</p>
                    </div>
                </div>

                <!-- Content -->
                <div class="app-content">
                    <div class="container-fluid">
                        <div class="card shadow-sm rounded-3">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Daftar Pengguna</h5>
                                <button type="button" class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="bi bi-plus-lg"></i> Tambah Pengguna
                                </button>
                            </div>
                            
                            <div class="card-body">
                                <?php if ($result->num_rows > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-striped">
                                            <tr>
                                                <th style="width: 50px;">#</th>
                                                <th>Username</th>
                                                <th>Nama Lengkap</th>
                                                <th>Email</th>
                                                <th style="width: 100px;">Role</th>
                                                <th style="width: 150px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            while ($user = $result->fetch_assoc()) {
                                                $isCurrentUser = ($user['username'] === $_SESSION['username']);
                                                echo "<tr" . ($isCurrentUser ? " class='table-info'" : "") . ">";
                                                echo "<td>" . $no++ . "</td>";
                                                echo "<td>" . htmlspecialchars($user['username']) . ($isCurrentUser ? " <small class='text-primary'>(Anda)</small>" : "") . "</td>";
                                                echo "<td>" . htmlspecialchars($user['nama_lengkap']) . "</td>";
                                                echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                                                
                                                // Badge untuk role
                                                $badgeClass = '';
                                                if ($user['role'] === 'admin') {
                                                    $badgeClass = 'bg-danger';
                                                } elseif ($user['role'] === 'siswa') {
                                                    $badgeClass = 'bg-success';
                                                } else {
                                                    $badgeClass = 'bg-secondary';
                                                }
                                                echo "<td><span class='badge " . $badgeClass . "'>" . htmlspecialchars($user['role']) . "</span></td>";
                                                
                                                // Tombol aksi
                                                echo "<td>
                                                        <button type='button' class='btn btn-sm btn-primary me-1' onclick='editUser(\"" . htmlspecialchars($user['username']) . "\", \"" . htmlspecialchars($user['nama_lengkap']) . "\", \"" . htmlspecialchars($user['email']) . "\", \"" . htmlspecialchars($user['role']) . "\")' title='Edit User'>
                                                            <i class='bi bi-pencil-square'></i>
                                                        </button>";
                                                
                                                if (!$isCurrentUser) {
                                                    echo "<button type='button' class='btn btn-sm btn-danger' onclick='deleteUser(\"" . htmlspecialchars($user['username']) . "\")' title='Hapus User'>
                                                            <i class='bi bi-trash'></i>
                                                          </button>";
                                                } else {
                                                    echo "<button type='button' class='btn btn-sm btn-secondary' disabled title='Tidak dapat menghapus akun sendiri'>
                                                            <i class='bi bi-trash'></i>
                                                          </button>";
                                                }
                                                
                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-person-x display-4 text-muted"></i>
                                    <h5 class="mt-3 text-muted">Tidak ada pengguna ditemukan</h5>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Modal Tambah User -->
        <div class="modal fade" id="addUserModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Pengguna Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="action" value="add">
                            
                            <div class="mb-3">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="username" required maxlength="50">
                                <div class="form-text">Username harus unik</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_lengkap" required maxlength="100">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" required maxlength="100">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password" required minlength="5">
                                <div class="form-text">Minimal 5 karakter</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select" name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="siswa">Siswa</option>
                                </select>
                                <div class="form-text">
                                    <strong>Admin:</strong> Dapat mengelola semua data<br>
                                    <strong>Siswa:</strong> Hanya dapat melihat data pribadi
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Tambah Pengguna</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit User -->
        <div class="modal fade" id="editUserModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Pengguna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" id="edit_username_hidden" name="username">
                            
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" id="edit_username" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_nama_lengkap" name="nama_lengkap" required maxlength="100">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="edit_email" name="email" required maxlength="100">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="edit_password" name="password" minlength="5">
                                <div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_role" name="role" required>
                                    <option value="admin">Admin</option>
                                    <option value="siswa">Siswa</option>
                                </select>
                                <div id="edit_role_warning" class="form-text text-warning" style="display: none;">
                                    <i class="bi bi-exclamation-triangle"></i> Anda sedang mengedit akun Anda sendiri
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Update Pengguna</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Hapus User -->
        <div class="modal fade" id="deleteUserModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus Pengguna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" id="delete_username" name="username">
                            
                            <div class="text-center mb-3">
                                <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                            </div>
                            <p class="text-center">Apakah Anda yakin ingin menghapus pengguna <strong id="delete_username_display"></strong>?</p>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle"></i> 
                                <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Ya, Hapus Pengguna</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php include 'footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.js"></script>

    <script>
        // Fungsi untuk edit user
        function editUser(username, nama_lengkap, email, role) {
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_username_hidden').value = username;
            document.getElementById('edit_nama_lengkap').value = nama_lengkap;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            document.getElementById('edit_password').value = '';
            
            // Tampilkan peringatan jika edit akun sendiri
            const currentUser = '<?php echo $_SESSION['username']; ?>';
            const warningDiv = document.getElementById('edit_role_warning');
            if (username === currentUser) {
                warningDiv.style.display = 'block';
            } else {
                warningDiv.style.display = 'none';
            }
            
            var editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            editModal.show();
        }

        // Fungsi untuk hapus user
        function deleteUser(username) {
            const currentUser = '<?php echo $_SESSION['username']; ?>';
            if (username === currentUser) {
                alert('Anda tidak dapat menghapus akun Anda sendiri!');
                return;
            }
            
            document.getElementById('delete_username').value = username;
            document.getElementById('delete_username_display').textContent = username;
            
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
            deleteModal.show();
        }

        // Reset form ketika modal ditutup
        document.getElementById('addUserModal').addEventListener('hidden.bs.modal', function () {
            document.querySelector('#addUserModal form').reset();
        });

        document.getElementById('editUserModal').addEventListener('hidden.bs.modal', function () {
            document.querySelector('#editUserModal form').reset();
        });
    </script>
</body>
</html>