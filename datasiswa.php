<?php
include "koneksi.php";
$db = new database();

// Handle form submission untuk update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $jeniskelamin = $_POST['jeniskelamin'];
    $kelas = $_POST['kelas'];
    $alamat = $_POST['alamat'];
    $nohp = $_POST['nohp'];
    $jurusan = $_POST['jurusan'];
    $agama = $_POST['agama'];

    $db->update_data_siswa($nisn, $nama, $jeniskelamin, $kelas, $alamat, $nohp, $jurusan, $agama);

    header("Location: datasiswa.php");
    exit();
}

// Filter parameters
$filter_nama = isset($_GET['filter_nama']) ? $_GET['filter_nama'] : '';
$filter_kelas = isset($_GET['filter_kelas']) ? $_GET['filter_kelas'] : '';
$filter_jurusan = isset($_GET['filter_jurusan']) ? $_GET['filter_jurusan'] : '';
$filter_jeniskelamin = isset($_GET['filter_jeniskelamin']) ? $_GET['filter_jeniskelamin'] : '';

// Pagination parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 50;
$offset = ($page - 1) * $limit;

// Get filtered data (Anda perlu menambahkan method ini di class database)
$data_siswa = $db->tampil_data_siswa_filtered($filter_nama, $filter_kelas, $filter_jurusan, $filter_jeniskelamin, $limit, $offset);
$total_data = $db->count_data_siswa_filtered($filter_nama, $filter_kelas, $filter_jurusan, $filter_jeniskelamin);
$total_pages = ceil($total_data / $limit);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Data Siswa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.css" />
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
<?php include "navbar.php"; ?>
<?php include "sidebar.php"; ?>
<div class="content-wrapper">
  <main class="app-main">
    <div class="app-content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6"><h3>Data Siswa</h3></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Data Siswa</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="app-content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            
            <!-- Filter Card -->
            <div class="card mb-3">
              <div class="card-header">
                <h5 class="card-title mb-0">Filter Data Siswa</h5>
              </div>
              <div class="card-body">
                <form method="GET" action="">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" name="filter_nama" 
                               value="<?= htmlspecialchars($filter_nama) ?>" 
                               placeholder="Cari berdasarkan nama...">
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <input type="text" class="form-control" name="filter_kelas" 
                               value="<?= htmlspecialchars($filter_kelas) ?>" 
                               placeholder="Contoh: XII-A">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="mb-3">
                        <label class="form-label">Jurusan</label>
                        <select name="filter_jurusan" class="form-select">
                          <option value="">-- Semua Jurusan --</option>
                          <?php foreach ($db->tampil_data_show_jurusan() as $jur) : ?>
                            <option value="<?= $jur['kodejurusan'] ?>" 
                                    <?= $filter_jurusan == $jur['kodejurusan'] ? 'selected' : '' ?>>
                              <?= $jur['namajurusan'] ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="filter_jeniskelamin" class="form-select">
                          <option value="">-- Semua --</option>
                          <option value="L" <?= $filter_jeniskelamin == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                          <option value="P" <?= $filter_jeniskelamin == 'P' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2">
                          <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                          </button>
                          <a href="datasiswa.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>

            <!-- Data Table Card -->
            <div class="card mb-4">
              <div class="card-header">
                <h3 class="card-title">
                  Data Siswa 
                  <small class="text-muted">
                    (Menampilkan <?= count($data_siswa) ?> dari <?= $total_data ?> data)
                  </small>
                </h3>
                <?php if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') : ?>
                <a href="tambahsiswa.php" class="btn btn-primary float-end">
                  <i class="bi bi-plus-circle"></i> Tambah Data
                </a>
                <?php endif; ?>
              </div>

              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-striped table-hover">
                    <thead class="table table-striped">
                      <tr>
                        <th style="width: 50px">No.</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Jurusan</th>
                        <th>Kelas</th>
                        <th>Alamat</th>
                        <th>Agama</th>
                        <th>No HP</th>
                        <th style="width: 120px">Opsi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($data_siswa)) : ?>
                        <?php 
                        $no = $offset + 1;
                        foreach($data_siswa as $X) : ?>
                        <tr class="align-middle">
                          <td><?= $no++ ?></td>
                          <td><?= htmlspecialchars($X['nisn']) ?></td>
                          <td><?= htmlspecialchars($X['nama']) ?></td>
                          <td>
                            <span class="badge <?= $X['jeniskelamin'] == 'L' ? 'bg-primary' : 'bg-info' ?>">
                              <?= $X['jeniskelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?>
                            </span>
                          </td>
                          <td><?= htmlspecialchars($X['namajurusan']) ?></td>
                          <td><?= htmlspecialchars($X['kelas']) ?></td>
                          <td><?= htmlspecialchars($X['alamat']) ?></td>
                          <td><?= htmlspecialchars($X['namaagama']) ?></td>
                          <td><?= htmlspecialchars($X['nohp']) ?></td>
                          <td>
                            <?php if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') : ?>
                            <!-- Tombol Edit -->
                            <button class="btn btn-warning btn-sm mb-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEdit<?= $X['nisn'] ?>">
                              <i class="bi bi-pencil"></i>
                            </button>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit<?= $X['nisn'] ?>" 
                                 data-bs-backdrop="static" 
                                 data-bs-keyboard="false" 
                                 tabindex="-1" 
                                 aria-labelledby="labelEdit<?= $X['nisn'] ?>" 
                                 aria-hidden="true">
                              <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                  <form action="" method="POST">
                                    <div class="modal-header bg-warning">
                                      <h5 class="modal-title" id="labelEdit<?= $X['nisn'] ?>">Edit Data Siswa</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      <input type="hidden" name="nisn" value="<?= $X['nisn'] ?>">
                                      <div class="mb-3">
                                        <label for="nama<?= $X['nisn'] ?>" class="form-label">Nama</label>
                                        <input type="text" class="form-control" id="nama<?= $X['nisn'] ?>" name="nama" value="<?= htmlspecialchars($X['nama']) ?>" required>
                                      </div>
                                      <div class="mb-3">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <select name="jeniskelamin" class="form-select">
                                          <option value="L" <?= $X['jeniskelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                          <option value="P" <?= $X['jeniskelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                                        </select>
                                      </div>
                                      <div class="mb-3">
                                        <label class="form-label">Jurusan</label>
                                        <select name="jurusan" class="form-select" required>
                                          <?php foreach ($db->tampil_data_show_jurusan() as $jur) : ?>
                                            <option value="<?= $jur['kodejurusan'] ?>" <?= $jur['kodejurusan'] == $X['kodejurusan'] ? 'selected' : '' ?>>
                                              <?= $jur['namajurusan'] ?>
                                            </option>
                                          <?php endforeach; ?>
                                        </select>
                                      </div>
                                      <div class="mb-3">
                                        <label class="form-label">Kelas</label>
                                        <input type="text" class="form-control" name="kelas" value="<?= htmlspecialchars($X['kelas']) ?>" required>
                                      </div>
                                      <div class="mb-3">
                                        <label class="form-label">Alamat</label>
                                        <input type="text" class="form-control" name="alamat" value="<?= htmlspecialchars($X['alamat']) ?>">
                                      </div>
                                      <div class="mb-3">
                                        <label class="form-label">Agama</label>
                                        <select name="agama" class="form-select" required>
                                          <?php foreach ($db->tampil_data_show_agama() as $agm) : ?>
                                            <option value="<?= $agm['kodeagama'] ?>" <?= $agm['kodeagama'] == $X['agama'] ? 'selected' : '' ?>>
                                              <?= $agm['namaagama'] ?>
                                            </option>
                                          <?php endforeach; ?>
                                        </select>
                                      </div>
                                      <div class="mb-3">
                                        <label class="form-label">No HP</label>
                                        <input type="text" class="form-control" name="nohp" value="<?= htmlspecialchars($X['nohp']) ?>">
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                      <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>

                            <!-- Tombol Hapus -->
                            <button class="btn btn-danger btn-sm mb-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalHapus<?= $X['nisn'] ?>">
                              <i class="bi bi-trash"></i>
                            </button>

                            <!-- Modal Konfirmasi Hapus -->
                            <div class="modal fade" id="modalHapus<?= $X['nisn'] ?>" 
                                 data-bs-backdrop="static" 
                                 data-bs-keyboard="false" 
                                 tabindex="-1" 
                                 aria-labelledby="labelHapus<?= $X['nisn'] ?>" 
                                 aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="labelHapus<?= $X['nisn'] ?>">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <p>Yakin ingin menghapus data siswa ini?</p>
                                    <ul class="list-unstyled">
                                      <li><strong>NISN:</strong> <?= htmlspecialchars($X['nisn']) ?></li>
                                      <li><strong>Nama:</strong> <?= htmlspecialchars($X['nama']) ?></li>
                                      <li><strong>Jenis Kelamin:</strong> <?= $X['jeniskelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></li>
                                      <li><strong>Jurusan:</strong> <?= htmlspecialchars($X['namajurusan']) ?></li>
                                      <li><strong>Kelas:</strong> <?= htmlspecialchars($X['kelas']) ?></li>
                                    </ul>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <a href="hapus_siswa.php?nisn=<?= $X['nisn'] ?>" class="btn btn-danger">Hapus</a>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <?php else: ?>
                            <span class="badge bg-secondary">View Only</span>
                            <?php endif; ?>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="10" class="text-center py-4">
                            <div class="text-muted">
                              <i class="bi bi-inbox fs-1"></i>
                              <p class="mt-2">Tidak ada data yang ditemukan</p>
                            </div>
                          </td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Pagination -->
              <?php if ($total_pages > 1) : ?>
              <div class="card-footer">
                <nav aria-label="Page navigation">
                  <ul class="pagination justify-content-center mb-0">
                    <!-- Previous Button -->
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                      <a class="page-link" href="?page=<?= $page - 1 ?>&filter_nama=<?= urlencode($filter_nama) ?>&filter_kelas=<?= urlencode($filter_kelas) ?>&filter_jurusan=<?= urlencode($filter_jurusan) ?>&filter_jeniskelamin=<?= urlencode($filter_jeniskelamin) ?>">
                        <i class="bi bi-chevron-left"></i>
                      </a>
                    </li>

                    <!-- Page Numbers -->
                    <?php 
                    $start_page = max(1, $page - 2);
                    $end_page = min($total_pages, $page + 2);
                    
                    if ($start_page > 1) : ?>
                      <li class="page-item">
                        <a class="page-link" href="?page=1&filter_nama=<?= urlencode($filter_nama) ?>&filter_kelas=<?= urlencode($filter_kelas) ?>&filter_jurusan=<?= urlencode($filter_jurusan) ?>&filter_jeniskelamin=<?= urlencode($filter_jeniskelamin) ?>">1</a>
                      </li>
                      <?php if ($start_page > 2) : ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                      <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($i = $start_page; $i <= $end_page; $i++) : ?>
                      <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&filter_nama=<?= urlencode($filter_nama) ?>&filter_kelas=<?= urlencode($filter_kelas) ?>&filter_jurusan=<?= urlencode($filter_jurusan) ?>&filter_jeniskelamin=<?= urlencode($filter_jeniskelamin) ?>"><?= $i ?></a>
                      </li>
                    <?php endfor; ?>

                    <?php if ($end_page < $total_pages) : ?>
                      <?php if ($end_page < $total_pages - 1) : ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                      <?php endif; ?>
                      <li class="page-item">
                        <a class="page-link" href="?page=<?= $total_pages ?>&filter_nama=<?= urlencode($filter_nama) ?>&filter_kelas=<?= urlencode($filter_kelas) ?>&filter_jurusan=<?= urlencode($filter_jurusan) ?>&filter_jeniskelamin=<?= urlencode($filter_jeniskelamin) ?>"><?= $total_pages ?></a>
                      </li>
                    <?php endif; ?>

                    <!-- Next Button -->
                    <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                      <a class="page-link" href="?page=<?= $page + 1 ?>&filter_nama=<?= urlencode($filter_nama) ?>&filter_kelas=<?= urlencode($filter_kelas) ?>&filter_jurusan=<?= urlencode($filter_jurusan) ?>&filter_jeniskelamin=<?= urlencode($filter_jeniskelamin) ?>">
                        <i class="bi bi-chevron-right"></i>
                      </a>
                    </li>
                  </ul>
                </nav>
                
                <div class="text-center mt-2">
                  <small class="text-muted">
                    Halaman <?= $page ?> dari <?= $total_pages ?> 
                    (Total: <?= $total_data ?> data)
                  </small>
                </div>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</div> 

<?php include "footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="dist/js/adminlte.js"></script>

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