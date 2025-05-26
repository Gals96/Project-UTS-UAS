<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
  echo "<script>alert('Anda harus login dulu!'); window.location='login.php';</script>";
  exit;
}

// Variable untuk menyimpan pesan
$success_message = "";
$error_message = "";


// Proses saat tombol submit ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $tenggat_waktu = $_POST['tenggat_waktu'];
    $id_user = $_SESSION['id_user'];

    if (!empty($judul) && !empty($tenggat_waktu)) {
        $query = "INSERT INTO tugas (judul, deskripsi, tenggat_waktu, id_user) 
                  VALUES ('$judul', '$deskripsi', '$tenggat_waktu', '$id_user')";

        if (mysqli_query($conn, $query)) {
            $success_message = "✅ Tugas berhasil ditambahkan.";
            header("refresh:2;url=daftartugas.php");
        } else {
            $error_message = "❌ Gagal menambahkan tugas: " . mysqli_error($conn);
        }
    } else {
        $error_message = "❗ Judul dan tenggat waktu harus diisi.";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Tugas</title>

  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg bg-light px-3 mb-4">
    <a class="navbar-brand" href="daftartugas.php"><i class="bi bi-arrow-left"></i> Kembali</a>
    <div class="ms-auto d-flex align-items-center">
      <img src="Pengguna.jpeg" class="rounded-circle" width="40" height="40" alt="User">
      <span class="ms-2"><b>@<?= $_SESSION['username'] ?></b></span>
    </div>
  </nav>

  <!-- Sidebar Menu -->
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
          <a class="nav-link text-white" href="profil.html"><i class="bi bi-person-circle me-2"></i>Profil</a>
        </li>
      </ul>
    </div>
  </div>

  <!-- Form -->
  <div class="form-container">
    <h3 class="text-center mb-4">Tambah Tugas Baru</h3>
    
    <?php if(!empty($success_message)): ?>
      <div class="alert alert-success">
        <?php echo $success_message; ?>
      </div>
    <?php endif; ?>
    
    <?php if(!empty($error_message)): ?>
      <div class="alert alert-danger">
        <?php echo $error_message; ?>
      </div>
    <?php endif; ?>
    
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="mb-3">
        <label for="judul" style="font-weight: 600">Nama Tugas <span class="text-danger">*</span></label>
        <input type="text" name="judul" id="judul" placeholder="Masukkan nama tugas" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="deskripsi" style="font-weight: 600">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" placeholder="Masukkan deskripsi tugas" class="form-control" rows="4"></textarea>
      </div>
      <div class="mb-3">
        <label for="tenggat_waktu" style="font-weight: 600">Deadline Tugas <span class="text-danger">*</span></label>
        <input type="date" name="tenggat_waktu" id="tenggat_waktu" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-danger" style="width: 100%; font-weight: bold; padding: 12px;" >SIMPAN TUGAS</button>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
