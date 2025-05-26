<?php 
include 'db.php'; 

$regis_status = "";
if (isset($_POST['register'])) {
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $username = $_POST['username'];
  $password = $_POST['password'];

  $check_query = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username' OR email = '$email'");
  
  if (mysqli_num_rows($check_query) > 0) {
    $regis_status = "exists";
  } else {
    $insert = mysqli_query($conn, "INSERT INTO user (nama, email, username, password) 
                                   VALUES ('$nama', '$email', '$username', '$password')");

    if ($insert) {
      $regis_status = "success";
    } else {
      $regis_status = "error";
    }
  }
}
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

  <div class="form-container" style="border: 2px solid #f0f0f0">
    <h3 class="text-center mb-4">Buat Akun Baru</h3>
    <form method="POST">
      <div class="mb-3">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" placeholder="Masukkan nama lengkap" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" placeholder="Masukkan email"  class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan password"  class="form-control" required>
      </div>
      <button type="submit" name="register" class="btn btn-primary">BUAT AKUN</button>
      <p class="text-center mt-2">Sudah punya akun? <a href="login.php">Login di sini!</a></p>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Script Register -->
  <script>
    const regisStatus = "<?php echo $regis_status; ?>";
    if (regisStatus === "success") {
      Swal.fire({
        icon: 'success',
        title: 'Registrasi Berhasil!',
        text: 'Akun berhasil dibuat. Mengalihkan ke halaman login...',
        showConfirmButton: false,
        timer: 2000
      }).then(() => {
        window.location.href = 'login.php';
      });
    } else if (regisStatus === "error") {
      Swal.fire({
        icon: 'error',
        title: 'Registrasi Gagal!',
        text: 'Terjadi kesalahan saat membuat akun. Silakan coba lagi.',
        confirmButtonColor: "#d33"
      });
    } else if (regisStatus === "exists") {
      Swal.fire({
        icon: 'warning',
        title: 'Akun Sudah Ada!',
        text: 'Username atau email sudah terdaftar. Gunakan yang lain.',
        confirmButtonColor: "#d33"
      });
    }
  </script>
</body>
</html>
