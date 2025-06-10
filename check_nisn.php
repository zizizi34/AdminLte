<?php
include 'koneksi.php';
$db = new database();

// Set header untuk JSON response
header('Content-Type: application/json');

if (isset($_POST['nisn'])) {
    $nisn = $_POST['nisn'];
    
    // Cek apakah NISN sudah ada
    $exists = $db->cek_nisn_exists($nisn);
    
    // Return JSON response
    echo json_encode(['exists' => $exists]);
} else {
    echo json_encode(['exists' => false]);
}
?>