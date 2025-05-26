<?php
session_start();
include 'db.php';

$login_status = "";
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
  $user = mysqli_fetch_assoc($query);

  if ($user && $password === $user['password']) {
    // Simpan data penting ke session
    $_SESSION['id_user'] = $user['id_user'];  // PENTING untuk foreign key
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama'] = $user['nama'];

    $login_status = "success";
    echo "<meta http-equiv='refresh' content='2; url=index.php'>";
  } else {
    $login_status = "error";
  }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <div class="form-container" style="border: 2px solid #f0f0f0">
    <h3 class="text-center mb-4">Login</h3>
    <form method="POST">
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan password" class="form-control" required>
      </div>
      <button type="submit" name="login" class="btn btn-success">LOG IN</button>
      <p class="text-center mt-2 mb-2"><a href="resetpw.php">Lupa Password</a></p>
      <p class="text-center">Belum punya akun? <a href="register.php">Daftar di sini!</a></p>
    </form>
    
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const loginStatus = "<?php echo $login_status; ?>";

    if (loginStatus === "success") {
      Swal.fire({
        icon: 'success',
        title: 'Login Berhasil!',
        text: 'Mengalihkan ke dashboard...',
        showConfirmButton: false,
        timer: 2000
      });
    } else if (loginStatus === "error") {
        Swal.fire({
        icon: 'error',
        title: 'Login Gagal!',
        text: 'Username atau password salah!',
        confirmButtonColor: "#d33"
      });
    }
  </script>
</body>
</html>
