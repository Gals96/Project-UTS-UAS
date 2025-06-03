<?php
include 'db.php';

$show_reset_form = false;
$reset_pw = "";

// Tahap 1: Cek jika form cari username dikirim
if (isset($_POST['cari'])) {
    $username = $_POST['username'];
    $query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);

    if ($user) {
        $show_reset_form = true; // Tampilkan form ganti password
    } else {
        $reset_pw = "username";
    }
}

// Tahap 2: Proses reset password
if (isset($_POST['reset'])) {
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];
    $hash = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password (tidak dienkripsi, untuk demo)
    $query = mysqli_query($conn, "UPDATE user SET password='$hash' WHERE username='$username'");

    if ($query) {
        $reset_pw = "success";
    } else {
        $reset_pw = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Lupa / Reset Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h3 class="mb-4">Lupa Password</h3>

    <!-- Tampilkan form cari username jika belum berhasil -->
    <?php if (!$show_reset_form && empty($success)): ?>
      <form method="POST">
        <div class="mb-3">
          <label>Masukkan Username Anda</label>
          <input type="text" name="username" placeholder="Masukkan username" class="form-control" required>
        </div>
        <button type="submit" name="cari" class="btn btn-primary">Cari Akun</button>
      </form>
    <?php endif; ?>

    <!-- Tampilkan form reset password jika user ditemukan -->
    <?php if ($show_reset_form): ?>
      <form method="POST" class="mt-4">
        <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">
        <div class="mb-3">
          <label>Password Baru</label>
          <input type="password" name="new_password" placeholder="Masukkan password baru" class="form-control" required>
        </div>
        <button type="submit" name="reset" class="btn btn-success">Reset Password</button>
      </form>
    <?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const resetPw = "<?php echo $reset_pw; ?>";

    if (resetPw === "success") {
      Swal.fire({
        icon: 'success',
        title: 'Password berhasil diubah',
        text: 'Silakan login kembali.',
        confirmButtonText: 'Login Sekarang',
        confirmButtonColor: '#198754'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'login.php';
        }
      });
    } else if (resetPw === "error") {
      Swal.fire({
        icon: 'error',
        title: 'Password gagal diubah',
        confirmButtonColor: "#d33"
      });
    } else if (resetPw === "username") {
      Swal.fire({
        icon: 'warning',
        title: 'Username tidak ditemukan',
        confirmButtonColor: "#d33"
      });
    }
  </script>
</body>
</html>
