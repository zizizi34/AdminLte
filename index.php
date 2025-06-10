<?php
session_start();

// Variabel untuk menyimpan pesan error
$error = '';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

// Proses login ketika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validasi input tidak boleh kosong
    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi!";
    } else {
        // Koneksi ke database
        include_once 'koneksi.php';
        $db = new database();

        // Query untuk mencari user berdasarkan username
        $sql = "SELECT id, username, password, email, role FROM users WHERE username = ?";
        $stmt = $db->koneksi->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Cek apakah user ditemukan
        if ($user = $result->fetch_assoc()) {
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Login berhasil - simpan data ke session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect ke dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Username tidak ditemukan!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SMK Negeri 6 Surakarta</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            display: flex;
            height: 100vh;
        }
        .container {
            display: flex;
            flex: 1;
        }
        .left {
            flex: 1;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: #ffffff;
        }
        .right {
            flex: 1;
            background: url('dist/assets/img/login.jpg') no-repeat center;
            background-size: cover;
        }
        h1 {
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #1f2937;
        }
        p {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 30px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        input[type="text"],
        input[type="password"] {
            padding: 12px 16px;
            font-size: 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }
        .btn {
            background-color: #3b82f6;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #2563eb;
        }
        .alert {
            background-color: #fecaca;
            color: #991b1b;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .demo-info {
            margin-top: 20px;
            font-size: 12px;
            background: #f3f4f6;
            padding: 10px;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
        }
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .right {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <h1>Hallo, Selamat Datang</h1>
            <p>SMK NEGERI 6 Surakarta</p>

            <!-- Pesan Error -->
            <?php if (!empty($error)): ?>
                <div class="alert"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Form Login -->
            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn">Sign In</button>
            </form>

            <!-- Info Demo untuk Presentasi -->
            <div class="demo-info">
                <em>*Password di database sudah di-enkripsi untuk keamanan</em>
            </div>
        </div>

        <div class="right"></div>
    </div>
</body>
</html>