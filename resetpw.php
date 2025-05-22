<?php
include 'db.php';
$username = '';
$show_reset_form = false;
$success = '';
$error = '';

// Tahap 1: Cek jika form cari username dikirim
if (isset($_POST['cari'])) {
    $username = $_POST['username'];
    $query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);

    if ($user) {
        $show_reset_form = true; // Tampilkan form ganti password
    } else {
        $error = "Username tidak ditemukan.";
    }
}

// Tahap 2: Proses reset password
if (isset($_POST['reset'])) {
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];

    // Update password (tidak dienkripsi, untuk demo)
    $query = mysqli_query($conn, "UPDATE user SET password='$new_password' WHERE username='$username'");

    if ($query) {
        $success = "Password berhasil diubah. <a href='login.php'>Login sekarang</a>.";
    } else {
        $error = "Gagal mengubah password.";
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
          <input type="text" name="username" placeholder="Masukkan username" class="form-control italic-placeholder" required>
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
          <input type="password" name="new_password" placeholder="Masukkan Password Baru" class="form-control italic-placeholder" required>
        </div>
        <button type="submit" name="reset" class="btn btn-success">Reset Password</button>
      </form>
    <?php endif; ?>

    <!-- Notifikasi -->
    <?php if (!empty($success)): ?>
      <div class="alert alert-success mt-3"><?= $success ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger mt-3"><?= $error ?></div>
    <?php endif; ?>
  </div>
</body>
</html>
