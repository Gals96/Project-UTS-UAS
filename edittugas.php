<?php
session_start();
include 'db.php';

// Ambil ID tugas dari URL
if(isset($_GET['id_tugas'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id_tugas']);
    $result = mysqli_query($conn, "SELECT * FROM tugas WHERE id_tugas='$id'");
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        $error_message = "Tugas tidak ditemukan!";
    }
} else {
    header("Location: daftartugas.php");
    exit();
}

// Proses saat form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $tenggat_waktu = $_POST['tenggat_waktu'];

    if(!empty($judul) && !empty($tenggat_waktu)) {
        $update_query = "UPDATE tugas SET judul='$judul', deskripsi='$deskripsi', tenggat_waktu='$tenggat_waktu' 
                        WHERE id_tugas='$id'";
        
        if(mysqli_query($conn, $update_query)) {
            $success_message = "Data berhasil diubah!";
            // Refresh data setelah update
            $result = mysqli_query($conn, "SELECT * FROM tugas WHERE id_tugas='$id'");
            $row = mysqli_fetch_assoc($result);
            // Redirect setelah beberapa detik
            header("refresh:2;url=daftartugas.php");
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Judul dan deadline harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Tugas - Tugasku</title>
  
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

  <!-- Form -->
  <div class="form-container">
    <h3 class="text-center mb-4">Edit Tugas</h3>
    
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
    
    <?php if(isset($row)): ?>
    <form method="POST" action="">
      <div class="mb-3">
        <label for="judul" style="font-weight: 600">Nama Tugas <span class="text-danger">*</span></label>
        <input type="text" name="judul" id="judul" value="<?= htmlspecialchars($row['judul']) ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="deskripsi" style="font-weight: 600">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" class="form-control"><?= htmlspecialchars($row['deskripsi']) ?></textarea>
      </div>
      <div class="mb-3">
        <label for="tenggat_waktu" style="font-weight: 600">Deadline Tugas <span class="text-danger">*</span></label>
        <input type="date" name="tenggat_waktu" id="tenggat_waktu" value="<?= date("Y-m-d", strtotime($row['tenggat_waktu'])) ?>" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-danger" style="width: 100%; font-weight: bold; padding: 12px;" >SIMPAN PERUBAHAN</button>
    </form>
    <?php endif; ?>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>