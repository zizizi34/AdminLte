<?php

if (!class_exists('database')) {

class database{
    var $host = "localhost";
    var $user = "root";
    var $password = "";
    var $database = "sekolah";

    function __construct(){
        $this->koneksi = mysqli_connect(
            $this->host,
            $this->user,
            $this->password,
            $this->database // <- Tambahin database di sini
        );
    
        if (!$this->koneksi) {
            die("Koneksi database gagal: " . mysqli_connect_error());
}
}

public function update_data_jurusan($kodejurusan, $namajurusan) {
    $query = "UPDATE kodejurusan SET 
              namajurusan='$namajurusan' 
              WHERE kodejurusan='$kodejurusan'";
    mysqli_query($this->koneksi, $query);
    if (mysqli_affected_rows($this->koneksi) > 0) {
        return true; // Update successful
    } else {
        return false; // Update failed
    }
}
    
    public function tampil_data_show_siswa() {
        $data = [];
        $query = "
        SELECT siswa.*, 
            CASE
                WHEN siswa.jeniskelamin='L' THEN 'Laki-laki'
                ELSE 'Perempuan'
            END as jeniskelamin,
            kodejurusan.namajurusan, 
            kodeagama.namaagama 
        FROM siswa 
        LEFT JOIN kodejurusan ON siswa.kodejurusan = kodejurusan.kodejurusan 
        LEFT JOIN kodeagama ON siswa.agama = kodeagama.kodeagama";
    
        $result = mysqli_query($this->koneksi, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    public function tampil_data_show_agama() {
        $data = [];
        $query = "SELECT * FROM kodeagama";
        $result = mysqli_query($this->koneksi, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    public function tampil_data_show_jurusan() {
        $data = [];
        $query = "SELECT * FROM kodejurusan";
        $result = mysqli_query($this->koneksi, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    function tambah_jurusan($kodejurusan, $namajurusan) {
        // Check if kodejurusan already exists
        $checkQuery = "SELECT kodejurusan FROM kodejurusan WHERE kodejurusan = '$kodejurusan'";
        $checkResult = mysqli_query($this->koneksi, $checkQuery);
        if (mysqli_num_rows($checkResult) > 0) {
            die("Kodejurusan '$kodejurusan' sudah ada, tidak bisa ditambahkan lagi.");
        }

        $query = "INSERT INTO kodejurusan (kodejurusan, namajurusan) 
                  VALUES ('$kodejurusan', '$namajurusan')";

        if (!mysqli_query($this->koneksi, $query)) {
            die("Query gagal: " . mysqli_error($this->koneksi) . " - Query: " . $query);
        }
    }

    // Menambah data agama
    function tambah_agama($kodeagama, $namaagama) {
        // Check if kodeagama already exists
        $checkQuery = "SELECT kodeagama FROM kodeagama WHERE kodeagama = '$kodeagama'";
        $checkResult = mysqli_query($this->koneksi, $checkQuery);
        if (mysqli_num_rows($checkResult) > 0) {
            die("Kodeagama '$kodeagama' sudah ada, tidak bisa ditambahkan lagi.");
        }

        $query = "INSERT INTO kodeagama (kodeagama, namaagama) VALUES ('$kodeagama', '$namaagama')";

        if (!mysqli_query($this->koneksi, $query)) {
            die("Query gagal: " . mysqli_error($this->koneksi) . " - Query: " . $query);
        }
    }

    public function tambah_data_siswa($nisn, $nama, $jeniskelamin, $jurusan, $kelas, $alamat, $agama, $nohp) {
        $query = "INSERT INTO siswa (nisn, nama, jeniskelamin, kodejurusan, kelas, alamat, agama, nohp) 
                  VALUES ('$nisn', '$nama', '$jeniskelamin', '$jurusan', '$kelas', '$alamat', '$agama', '$nohp')";
        $result = mysqli_query($this->koneksi, $query);
    
        if (!$result) {
            echo "Query Error: " . mysqli_error($this->koneksi);
        }
    
        return $result;
    }
    public function update_data_siswa($nisn, $nama, $jeniskelamin, $kelas, $alamat, $nohp, $jurusan, $agama) {
        $query = "UPDATE siswa SET 
                  nama='$nama', 
                  jeniskelamin='$jeniskelamin', 
                  kelas='$kelas', 
                  alamat='$alamat', 
                  nohp='$nohp',
                  kodejurusan='$jurusan',
                  agama='$agama'
                  WHERE nisn='$nisn'";
        mysqli_query($this->koneksi, $query);
        if (mysqli_affected_rows($this->koneksi) > 0) {
            return true; // Update successful
        } else {
            return false; // Update failed
        }       
    }
    public function cek_nisn_sudah_ada($nisn) {
    try {
        $sql = "SELECT COUNT(*) as count FROM siswa WHERE nisn = ?";
        $stmt = $this->koneksi->prepare($sql);
        $stmt->bind_param("s", $nisn);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'] > 0;
    } catch (Exception $e) {
        return false;
    }
}

    public function update_data_agama($kodeagama, $namaagama) {
        $query = "UPDATE kodeagama SET 
                  namaagama='$namaagama' 
                  WHERE kodeagama='$kodeagama'";
        mysqli_query($this->koneksi, $query);
        if (mysqli_affected_rows($this->koneksi) > 0) {
            return true; // Update successful
        } else {
            return false; // Update failed
        }
    }

public function hapus_data_agama($kodeagama) {
        $stmt = $this->koneksi->prepare("DELETE FROM kodeagama WHERE kodeagama = ?");
        $stmt->bind_param("s", $kodeagama);
        $stmt->execute();
        $stmt->close();
    }
// Tambahkan method-method ini ke dalam class database() di file koneksi.php

// Method untuk menampilkan data siswa dengan filter dan pagination (menggunakan MySQLi)
public function tampil_data_siswa_filtered($nama = '', $kelas = '', $jurusan = '', $jeniskelamin = '', $limit = 50, $offset = 0) {
    $sql = "SELECT 
                siswa.nisn, 
                siswa.nama, 
                siswa.jeniskelamin, 
                siswa.kelas, 
                siswa.alamat, 
                siswa.nohp,
                siswa.kodejurusan,
                siswa.agama,
                kodejurusan.namajurusan,
                kodeagama.namaagama
            FROM siswa 
            LEFT JOIN kodejurusan ON siswa.kodejurusan = kodejurusan.kodejurusan
            LEFT JOIN kodeagama ON siswa.agama = kodeagama.kodeagama
            WHERE 1=1";
    
    // Filter berdasarkan nama
    if (!empty($nama)) {
        $nama = mysqli_real_escape_string($this->koneksi, $nama);
        $sql .= " AND siswa.nama LIKE '%$nama%'";
    }
    
    // Filter berdasarkan kelas
    if (!empty($kelas)) {
        $kelas = mysqli_real_escape_string($this->koneksi, $kelas);
        $sql .= " AND siswa.kelas LIKE '%$kelas%'";
    }
    
    // Filter berdasarkan jurusan
    if (!empty($jurusan)) {
        $jurusan = mysqli_real_escape_string($this->koneksi, $jurusan);
        $sql .= " AND siswa.kodejurusan = '$jurusan'";
    }
    
    // Filter berdasarkan jenis kelamin
    if (!empty($jeniskelamin)) {
        $jeniskelamin = mysqli_real_escape_string($this->koneksi, $jeniskelamin);
        $sql .= " AND siswa.jeniskelamin = '$jeniskelamin'";
    }
    
    // Order by nama
    $sql .= " ORDER BY siswa.nama ASC";
    
    // Limit dan offset untuk pagination
    $sql .= " LIMIT $limit OFFSET $offset";
    
    $data = [];
    $result = mysqli_query($this->koneksi, $sql);
    
    if (!$result) {
        die("Query Error: " . mysqli_error($this->koneksi));
    }
    
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    return $data;
}

// Method untuk menghitung total data dengan filter
public function count_data_siswa_filtered($nama = '', $kelas = '', $jurusan = '', $jeniskelamin = '') {
    $sql = "SELECT COUNT(*) as total
            FROM siswa 
            LEFT JOIN kodejurusan ON siswa.kodejurusan = kodejurusan.kodejurusan
            LEFT JOIN kodeagama ON siswa.agama = kodeagama.kodeagama
            WHERE 1=1";
    
    // Filter berdasarkan nama
    if (!empty($nama)) {
        $nama = mysqli_real_escape_string($this->koneksi, $nama);
        $sql .= " AND siswa.nama LIKE '%$nama%'";
    }
    
    // Filter berdasarkan kelas
    if (!empty($kelas)) {
        $kelas = mysqli_real_escape_string($this->koneksi, $kelas);
        $sql .= " AND siswa.kelas LIKE '%$kelas%'";
    }
    
    // Filter berdasarkan jurusan
    if (!empty($jurusan)) {
        $jurusan = mysqli_real_escape_string($this->koneksi, $jurusan);
        $sql .= " AND siswa.kodejurusan = '$jurusan'";
    }
    
    // Filter berdasarkan jenis kelamin
    if (!empty($jeniskelamin)) {
        $jeniskelamin = mysqli_real_escape_string($this->koneksi, $jeniskelamin);
        $sql .= " AND siswa.jeniskelamin = '$jeniskelamin'";
    }
    
    $result = mysqli_query($this->koneksi, $sql);
    
    if (!$result) {
        die("Query Error: " . mysqli_error($this->koneksi));
    }
    
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

// Method untuk mendapatkan semua kelas yang ada (untuk autocomplete atau dropdown kelas)
public function get_all_kelas() {
    $sql = "SELECT DISTINCT kelas FROM siswa WHERE kelas IS NOT NULL AND kelas != '' ORDER BY kelas ASC";
    $data = [];
    $result = mysqli_query($this->koneksi, $sql);
    
    if (!$result) {
        die("Query Error: " . mysqli_error($this->koneksi));
    }
    
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    return $data;
}

// Method untuk mendapatkan statistik data siswa (opsional)
public function get_siswa_statistics() {
    $stats = [];
    
    // Total siswa
    $query = "SELECT COUNT(*) as total_siswa FROM siswa";
    $result = mysqli_query($this->koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $stats['total_siswa'] = $row['total_siswa'];
    
    // Total siswa laki-laki
    $query = "SELECT COUNT(*) as total_laki FROM siswa WHERE jeniskelamin = 'L'";
    $result = mysqli_query($this->koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $stats['total_laki'] = $row['total_laki'];
    
    // Total siswa perempuan
    $query = "SELECT COUNT(*) as total_perempuan FROM siswa WHERE jeniskelamin = 'P'";
    $result = mysqli_query($this->koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $stats['total_perempuan'] = $row['total_perempuan'];
    
    // Siswa per jurusan
    $query = "SELECT kodejurusan.namajurusan, COUNT(*) as jumlah 
              FROM siswa 
              LEFT JOIN kodejurusan ON siswa.kodejurusan = kodejurusan.kodejurusan 
              GROUP BY siswa.kodejurusan 
              ORDER BY jumlah DESC";
    $result = mysqli_query($this->koneksi, $query);
    $stats['per_jurusan'] = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $stats['per_jurusan'][] = $row;
    }
    
    return $stats;
}

// Method untuk hapus data siswa (jika belum ada)
public function hapus_data_siswa($nisn) {
    $nisn = mysqli_real_escape_string($this->koneksi, $nisn);
    $query = "DELETE FROM siswa WHERE nisn = '$nisn'";
    $result = mysqli_query($this->koneksi, $query);
    
    if (!$result) {
        die("Query Error: " . mysqli_error($this->koneksi));
    }
    
    return mysqli_affected_rows($this->koneksi) > 0;
}

// Method untuk cek apakah NISN sudah ada (untuk validasi)
public function cek_nisn_exists($nisn, $exclude_nisn = '') {
    $nisn = mysqli_real_escape_string($this->koneksi, $nisn);
    $query = "SELECT nisn FROM siswa WHERE nisn = '$nisn'";
    
    if (!empty($exclude_nisn)) {
        $exclude_nisn = mysqli_real_escape_string($this->koneksi, $exclude_nisn);
        $query .= " AND nisn != '$exclude_nisn'";
    }
    
    $result = mysqli_query($this->koneksi, $query);
    return mysqli_num_rows($result) > 0;
}
}
}

   
