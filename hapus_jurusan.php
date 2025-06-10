<?php
include "koneksi.php";

if (isset($_GET['kodejurusan'])) {
    $kodejurusan = $_GET['kodejurusan'];

    $db = new database();

    // Check if kodejurusan exists
    $checkExistQuery = "SELECT * FROM kodejurusan WHERE kodejurusan = '$kodejurusan'";
    $checkExistResult = mysqli_query($db->koneksi, $checkExistQuery);

    if (mysqli_num_rows($checkExistResult) == 0) {
        echo "Jurusan dengan kode '$kodejurusan' tidak ditemukan.";
        exit();
    }

    // Check if any siswa reference this kodejurusan
    $checkReferenceQuery = "SELECT * FROM siswa WHERE kodejurusan = '$kodejurusan'";
    $checkReferenceResult = mysqli_query($db->koneksi, $checkReferenceQuery);

    if (mysqli_num_rows($checkReferenceResult) > 0) {
        echo "Jurusan dengan kode '$kodejurusan' tidak dapat dihapus karena masih digunakan oleh data siswa.";
        exit();
    }

    // Delete jurusan from database
    $deleteQuery = "DELETE FROM kodejurusan WHERE kodejurusan = '$kodejurusan'";
    $deleteResult = mysqli_query($db->koneksi, $deleteQuery);

    if ($deleteResult) {
        // Redirect to datajurusan.php after successful deletion
        header("Location: datajurusan.php");
        exit();
    } else {
        echo "Error deleting jurusan: " . mysqli_error($db->koneksi);
    }
} else {
    echo "Kodejurusan tidak ditentukan.";
}
?>
