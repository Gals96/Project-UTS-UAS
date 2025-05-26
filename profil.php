<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
  echo "<script>alert('Anda harus login dulu!'); window.location='login.php';</script>";
  exit;
}

$username = $_SESSION['username'];
$query = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Profil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
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
          <a class="nav-link text-white" href="#" onclick="logout(); return false;">
          <i class="bi bi-box-arrow-right me-2"></i>Logout</a>
        </li>
      </ul>
    </div>
  </div>

  <div class="container d-flex justify-content-center" >
    <div class="profile-box" style="border: 2px solid #f0f0f0">
      <div class="d-flex align-items-center mb-3">
        <img src="Pengguna.jpeg" alt="Profile" class="profile-img me-3" />
        <div>
          <h5 class="mb-0"><?= htmlspecialchars($user['nama']) ?></h5>
        </div>
      </div>

      <h6 class="mb-3">Tentang Saya</h6>

      <!-- Username -->
      <div class="profile-info-row" >
        <div class="d-flex align-items-center">
          <div class="icon-box me-2"><i class="bi bi-person"></i></div>
          <div>
            <label style="font-size: 14px; color: gray">Pengguna</label><br>
            <div>@<?= htmlspecialchars($user['username']) ?></div>
          </div>
        </div>
        <a href="editprofil.php?field=username" style="color: #000"><i class="bi bi-pencil"></i></a>
      </div>

      <!-- Email -->
      <div class="profile-info-row">
        <div class="d-flex align-items-center">
          <div class="icon-box me-2"><i class="bi bi-envelope"></i></div>
          <div>
            <label style="font-size: 14px; color: gray">Alamat Email</label><br>
            <div><?= htmlspecialchars($user['email']) ?></div>
          </div>
        </div>
        <a href="editprofil.php?field=email" style="color: #000"><i class="bi bi-pencil"></i></a>
      </div>

      
<!-- Password -->
<div class="profile-info-row justify-content-between align-items-center">
  <div class="d-flex align-items-center">
    <div class="icon-box me-2"><i class="bi bi-key"></i></div>
    <div>
      <label style="font-size: 14px; color: gray">Password</label><br>
      <input type="password" id="password" class="form-control password-input" value="<?= htmlspecialchars($user['password']) ?>" readonly />
    </div>
  </div>

  <!-- Ikon mata & pensil -->
  <div class="d-flex align-items-center">
    <i class="bi bi-eye" id="togglePassword" style="cursor: pointer; margin-right: 10px;"></i>
    <a href="editprofil.php?field=password" style="color: #000"><i class="bi bi-pencil"></i></a>
  </div>
</div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    document.getElementById('togglePassword').addEventListener('click', function () {
      const passwordInput = document.getElementById('password');
      const type = passwordInput.type === 'password' ? 'text' : 'password';
      passwordInput.type = type;
      this.classList.toggle('bi-eye');
      this.classList.toggle('bi-eye-slash');
    });
  </script>

  <!-- Script Logout -->
  <script>
    function logout() {
      Swal.fire({
        title: "Yakin ingin keluar?",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "Batal",
        confirmButtonColor: "#198754",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yakin"
      }).then((result) => {
        if (result.isConfirmed) {
            window.location = 'logout.php';
        }
      }); 
    }
  </script>
</body>
</html>
