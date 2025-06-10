<?php
include "koneksi.php";

if (isset($_GET['nisn'])) {
    $nisn = $_GET['nisn'];

    $db = new database();

    // Check if nisn exists
    $checkExistQuery = "SELECT * FROM siswa WHERE nisn = '$nisn'";
    $checkExistResult = mysqli_query($db->koneksi, $checkExistQuery);

    if (mysqli_num_rows($checkExistResult) == 0) {
        echo "Siswa dengan NISN '$nisn' tidak ditemukan.";
        exit();
    }

    // Delete siswa from database
    $deleteQuery = "DELETE FROM siswa WHERE nisn = '$nisn'";
    $deleteResult = mysqli_query($db->koneksi, $deleteQuery);

    if ($deleteResult) {
        // Redirect to datasiswa.php after successful deletion
        header("Location: datasiswa.php");
        exit();
    } else {
        echo "Error deleting siswa: " . mysqli_error($db->koneksi);
    }
} else {
    echo "NISN tidak ditentukan.";
}
?>
