<?php
  session_start();
  include 'db.php';
  include 'functions.php';

  if (!isset($_SESSION['username'])) {
    echo "<script>alert('Anda harus login dulu!'); window.location='login.php';</script>";
    exit;
  }
  $id_user = $_SESSION['id_user'];
  $result = mysqli_query($conn, "SELECT * FROM tugas WHERE id_user = $id_user ORDER BY tenggat_waktu ASC LIMIT 2");
  $tugas = [];
  while ($row = mysqli_fetch_assoc($result)) {
      $tugas[] = $row;
  }  
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tugasku</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Navbar Atas -->
  <nav class="navbar navbar-expand-lg bg-light px-3">
    <button class="btn text-dark me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
      <i class="bi bi-three-dots-vertical fs-4"></i>
    </button>
    <form class="d-flex flex-grow-1">
      <input class="form-control me-2" type="search" placeholder="Cari tugas kamu" aria-label="Search" id="searchInput">
    </form>
    <div class="d-flex align-items-center ms-3">
      <img src="Pengguna.jpeg" class="rounded-circle" width="40" height="40" alt="User">
      <span class="ms-2"><b>@<?= $_SESSION['username'] ?></b></span>
    </div>
  </nav>

  <!-- Sidebar Hitam -->
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

  <!-- Banner Section -->
  <section class="banner">
    <h1>Selamat datang di <strong>Tugasku</strong></h1>
    <p>Atur tugasmu, dimanapun dan kapanpun!</p>
    <a href="daftartugas.php" class="btn btn-danger">Eksplore Tugas</a>
  </section>

  <!-- Daftar Tugas -->
  <div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4>Tugas Kamu</h4>
      <a href="tambahtugas.php" class="btn btn-danger">+ Tugas</a>
    </div>

    <div class="row g-3" id="tugasContainer">
    <?php if (!empty($tugas)): ?>
      <?php foreach ($tugas as $item): 
        $slug = slugify($item['judul']);
        $url = "rinciantugas.php?id={$item['id_tugas']}&judul={$slug}";
      ?>
        <div class="col-md-6 tugas-item">
          <div class="task-card">
            <div class="d-flex align-items-center mb-2">
              <img src="Icon.jpeg" alt="Icon" width="80" class="me-2">
              <div>
                <h5>
                  <a href="<?= $url ?>" class="text-decoration-none text-dark">
                    <?= htmlspecialchars($item['judul']) ?>
                  </a>
                </h5>
                <p class="mb-1"><?= htmlspecialchars($item['deskripsi']) ?></p>
                <span class="deadline">Deadline <?= formatTanggalIndo($item['tenggat_waktu']) ?></span>
              </div>
            </div>
            <a href="edittugas.php?id_tugas=<?= $item['id_tugas'] ?>" class="btn btn-outline-danger">Edit Tugas</a>
            <a href="hapus.php?id_tugas=<?= $item['id_tugas'] ?>" class="btn btn-outline-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
            <?php if ($item['status'] === 'Selesai'): ?>
              <span class="btn disable" style="color: green"><b>Selesai</b></span>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-muted">Belum ada tugas. Tambahkan tugas baru!</p>
    <?php endif; ?>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script Pencarian -->
  <script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
      var input = this.value.toLowerCase();
      var tugasItems = document.querySelectorAll('.tugas-item');

      tugasItems.forEach(function (item) {
        var text = item.textContent.toLowerCase();
        item.style.display = text.includes(input) ? '' : 'none';
      });
    });
  </script>
</body>
</html>
