<?php
session_start();

if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    $_SESSION = [];
    session_destroy();

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Logout</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f7fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .modal {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 30px 40px;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        .modal h2 {
            font-size: 24px;
            color: #2d3748;
            margin-bottom: 15px;
        }

        .modal p {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 25px;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn-cancel {
            background-color: #e2e8f0;
            color: #2d3748;
        }

        .btn-cancel:hover {
            background-color: #cbd5e0;
        }

        .btn-confirm {
            background-color: #e53e3e;
            color: #ffffff;
        }

        .btn-confirm:hover {
            background-color: #c53030;
        }

        @media (max-width: 480px) {
            .modal {
                padding: 20px;
            }

            .modal h2 {
                font-size: 20px;
            }

            .modal p {
                font-size: 14px;
            }

            .btn {
                width: 100%;
                padding: 12px 0;
            }

            .button-group {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="modal">
        <h2>Konfirmasi Logout</h2>
        <p>Apakah Anda yakin ingin keluar dari sesi ini?</p>
        <div class="button-group">
            <a href="javascript:history.back()" class="btn btn-cancel">Batalkan</a>
            <a href="?confirm=yes" class="btn btn-confirm">Keluar</a>
        </div>
    </div>
</body>
</html>
