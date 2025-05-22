<?php 
include 'db.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registrasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <div class="form-container">
    <h3 class="text-center mb-4">Buat Akun Baru</h3>
    <form method="POST">
      <div class="mb-3">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" placeholder="Masukkan Nama Lengkap" class="form-control italic-placeholder" required>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" placeholder="Masukkan email" class="form-control italic-placeholder" required>
      </div>
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan Username" class="form-control italic-placeholder" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan Password" class="form-control italic-placeholder" required>
      </div>
      <button type="submit" name="register" class="btn btn-primary">BUAT AKUN</button>
      <p class="text-center mt-2">Sudah punya akun? <a href="login.php">Login di sini!</a></p>
    </form>

    <?php
      if (isset($_POST['register'])) {
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        $insert = mysqli_query($conn, "INSERT INTO user (nama, email, username, password) 
                                       VALUES ('$nama', '$email', '$username', '$password')");

        if ($insert) {
          echo "<div class='alert alert-success mt-3'>Registrasi berhasil! Silakan <a href='login.php'>login</a>.</div>";
        } else {
          echo "<div class='alert alert-danger mt-3'>Gagal registrasi: " . mysqli_error($conn) . "</div>";
        }
      }
    ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
