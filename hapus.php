<?php
session_start();
include 'db.php';


if (isset($_GET['id_tugas'])) {
    $id_tugas = mysqli_real_escape_string($conn, $_GET['id_tugas']);

    
    $hapus_berkas = "DELETE FROM berkas WHERE id_tugas = '$id_tugas'";
    mysqli_query($conn, $hapus_berkas); // Abaikan error di sini agar tetap lanjut menghapus tugas

    
    $hapus_tugas = "DELETE FROM tugas WHERE id_tugas = '$id_tugas'";
    
    if (mysqli_query($conn, $hapus_tugas)) {
        $_SESSION['message'] = "Tugas berhasil dihapus";
    } else {
        $_SESSION['message'] = "Error saat menghapus tugas: " . mysqli_error($conn);
    }
} else {
    $_SESSION['message'] = "ID tugas tidak ditemukan";
}

// Redirect kembali ke halaman daftar tugas
header("Location: daftartugas.php");
exit();
?>
