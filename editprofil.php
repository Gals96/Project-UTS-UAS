<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
  echo "<script>alert('Anda harus login dulu!'); window.location='login.php';</script>";
  exit;
}

$edit_profil = "";
$field = $_GET['field'] ?? '';
$username = $_SESSION['username'];

// Ambil data lama
$query = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
$user = mysqli_fetch_assoc($query);

// Simpan perubahan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $new_value = mysqli_real_escape_string($conn, $_POST['new_value']);

  $update_query = "";
  if ($field == 'username') {
    $update_query = "UPDATE user SET username = '$new_value' WHERE username = '$username'";
  } elseif ($field == 'email') {
    $update_query = "UPDATE user SET email = '$new_value' WHERE username = '$username'";
  } elseif ($field == 'password') {
    $update_query = "UPDATE user SET password = '$new_value' WHERE username = '$username'";
  }

  if (mysqli_query($conn, $update_query)) {
    if ($field == 'username') {
      $_SESSION['username'] = $new_value;
    }
    $edit_profil = "success";
    echo "<meta http-equiv='refresh' content='2; url=profil.php'>";
  } else {
    $edit_profil = "error";
  }
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
            <input type="<?= $field === 'password' ? 'password' : 'text' ?>" name="new_value" class="form-control" value="<?= htmlspecialchars($user[$field]) ?>" required>
          </div>
          <button type="submit" class="btn btn-primary">Simpan</button>
          <a href="profil.php" class="btn btn-secondary">Batal</a>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const editProfil = "<?php echo $edit_profil; ?>";

    if (editProfil === "success") {
      Swal.fire({
        icon: 'success',
        title: 'Data berhasil diubah',
        text: 'Mengalihkan ke profil...',
        showConfirmButton: false,
        timer: 2000
      });
    } else if (editProfil === "error") {
      Swal.fire({
        icon: 'error',
        title: 'Data gagal diubah',
        confirmButtonColor: "#d33"
      });
    }
  </script>
</body>
</html>
