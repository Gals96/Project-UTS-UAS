<?php
session_start();
include 'db.php';
include 'functions.php';

$id_tugas = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM tugas WHERE id_tugas = $id_tugas");
$tugas = mysqli_fetch_assoc($query);
if (!$tugas) {
  echo "<script>alert('Tugas tidak ditemukan'); window.location='dashboard.php';</script>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_berkas']) && isset($_POST['berkas_terpilih'])) {
  foreach ($_POST['berkas_terpilih'] as $id_berkas) {
      $id_berkas = intval($id_berkas);

      // Ambil nama file dulu
      $file_query = mysqli_query($conn, "SELECT nama_file FROM berkas WHERE id_berkas = $id_berkas AND id_tugas = $id_tugas");
      $file_data = mysqli_fetch_assoc($file_query);
      if ($file_data) {
          $file_path = 'uploads/' . $file_data['nama_file'];
          if (file_exists($file_path)) {
              unlink($file_path); // hapus file fisik
          }
          // Hapus dari database
          mysqli_query($conn, "DELETE FROM berkas WHERE id_berkas = $id_berkas");
      }
  }

  // Redirect agar tidak kirim ulang POST
  header("Location: rinciantugas.php?id=" . $id_tugas);
  exit();
}

$berkas_query = mysqli_query($conn, "SELECT * FROM berkas WHERE id_tugas = $id_tugas");
$daftar_berkas = [];
while ($row = mysqli_fetch_assoc($berkas_query)) {
    $daftar_berkas[] = $row;
}

// Tandai selesai
if (isset($_POST['selesai'])) {
  mysqli_query($conn, "UPDATE tugas SET status='Selesai' WHERE id_tugas=$id_tugas");
  header("Location: rinciantugas.php?id=$id_tugas");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Rincian Tugas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg bg-light px-3 justify-content-between">
    <button class="btn text-dark me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
      <i class="bi bi-three-dots-vertical fs-4"></i>
    </button>
    <div class="d-flex align-items-center ms-3">
      <img src="Pengguna.jpeg" class="rounded-circle" width="40" height="40" alt="User">
      <span class="ms-2"><b>@<?= $_SESSION['username'] ?></b></span>
    </div>
  </nav>

  <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="sidebarMenu">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title">Tugasku</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
      <ul class="nav flex-column">
        <li class="nav-item mb-2">
          <a class="nav-link text-white" href="index.php"><i class="bi bi-house-door me-2"></i>Dashboard</a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link text-white" href="tambahtugas.php"><i class="bi bi-plus-square me-2"></i>Tambah Tugas</a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link text-white" href="daftartugas.php"><i class="bi bi-pencil-square me-2"></i>Daftar Tugas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="profil.php"><i class="bi bi-person-circle me-2"></i>Profil</a>
        </li>
        <li class="nav-item mt-3 border-top pt-3">
          <a class="nav-link text-white" href="logout.php" onclick="return confirm('Yakin ingin logout?')">
          <i class="bi bi-box-arrow-right me-2"></i>Logout</a>
        </li>
      </ul>
    </div>
  </div>

  <h2 class="text-center mt-4">Rincian Tugas</h2>
  <div class="task-details-box">
    <div class="d-flex align-items-center mb-3">
          <img src="Icon.jpeg" width="80" class="me-3" alt="Icon">
          <div>
              <h4><b><?= htmlspecialchars($tugas['judul']); ?></b></h4>
              <p style="font-size: 18px;"><?= htmlspecialchars($tugas['deskripsi']); ?></p>
          </div>
      </div>

    <?php if (count($daftar_berkas) > 0): ?>
      <form method="POST" onsubmit="return confirm('Yakin ingin menghapus berkas yang dipilih?');">
        <?php foreach ($daftar_berkas as $b): ?>
          <div class="mb-2 p-2 border rounded d-flex align-items-center bg-light" style="max-width: 600px;">
              <input type="checkbox" name="berkas_terpilih[]" value="<?= $b['id_berkas'] ?>" class="form-check-input me-3">
              <img src="ikon berkas.jpg" width="50" class="me-3" alt="Berkas Icon">
              <div class="flex-grow-1">
                  <div class="fw-semibold"><?= htmlspecialchars($b['nama_berkas'] ?: $b['nama_file']) ?></div>
                  <a href="berkas/<?= htmlspecialchars($b['nama_file']) ?>" target="_blank" class="small text-primary">Lihat Berkas</a>
              </div>
          </div>
        <?php endforeach; ?>
        <input type="hidden" name="hapus_berkas" value="1">
        <button type="submit" class="btn btn-secondary mt-3">Hapus Berkas Terpilih</button>
      </form>
    <?php endif; ?>

    <?php if (empty($daftar_berkas)): ?>
      <form action="uploadberkas.php" method="POST" enctype="multipart/form-data" style="max-width: 700px; margin: 20px auto;">
        <input type="hidden" name="id_tugas" value="<?= $id_tugas ?>">
        <div class="mb-3">
            <label for="nama_berkas" style="font-weight: 600">Nama Berkas (tampilan):</label>
            <input type="text" name="nama_berkas" id="nama_berkas" class="form-control" placeholder="Misal: Laporan Tugas" required>
        </div>
        <div class="mb-3">
            <label for="file_berkas" style="font-weight: 600">Pilih File:</label>
            <input type="file" name="file_berkas" id="file_berkas" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-secondary">Upload</button>
      </form>
    <?php endif; ?>

    <div class="mt-4 d-flex justify-content-between align-items-center">
      <div>
        <a href="edittugas.php?id_tugas=<?= $id_tugas ?>" class="btn btn-danger me-2 mb-2">Edit Tugas</a>
        <a href="hapus.php?id_tugas=<?= $id_tugas ?>" class="btn btn-danger me-2 mb-2" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
        <?php if ($tugas['status'] === 'Selesai'): ?>
          <span style="color: green; font-size: 15px; margin-left: 10px"><b>Tugas sudah selesai</b></span>
        <?php else: ?>
          <form method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menandai tugas ini sebagai selesai?');">
            <button name="selesai" type="submit" class="btn btn-dark mb-2"><b>Tandai Selesai</b></button>
          </form>
        <?php endif; ?>
      </div>
      <span class="deadline">Deadline <?= formatTanggalIndo($tugas['tenggat_waktu']) ?></span>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>