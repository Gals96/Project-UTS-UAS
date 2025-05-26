<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
  echo "<script>alert('Anda harus login dulu!'); window.location='login.php';</script>";
  exit;
}

$field = $_GET['field'] ?? '';
$username = $_SESSION['username'];

// Ambil data lama
$query = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
$user = mysqli_fetch_assoc($query);

// Simpan perubahan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $new_value = mysqli_real_escape_string($conn, $_POST['new_value']);

  switch ($field) {
    case 'username':
      mysqli_query($conn, "UPDATE user SET username = '$new_value' WHERE username = '$username'");
      $_SESSION['username'] = $new_value;
      break;
    case 'email':
      mysqli_query($conn, "UPDATE user SET email = '$new_value' WHERE username = '$username'");
      break;
    case 'password':
      mysqli_query($conn, "UPDATE user SET password = '$new_value' WHERE username = '$username'");
      break;
  }

  echo "<script>alert('Data berhasil diubah!'); window.location='profil.php';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Profil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="card mx-auto" style="max-width: 500px;">
      <div class="card-header">Edit <?= ucfirst($field) ?></div>
      <div class="card-body">
        <form method="post">
          <div class="mb-3">
            <label style="font-weight: 600"><?= ucfirst($field) ?> Baru</label>
            <input type="<?= $field === 'password' ? 'password' : 'text' ?>" name="new_value" placeholder="Masukkan username baru Anda" class="form-control" value="<?= htmlspecialchars($user[$field]) ?>" required>
          </div>
          <button type="submit" class="btn btn-primary">Simpan</button>
          <a href="profil.php" class="btn btn-secondary">Batal</a>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
