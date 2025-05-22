<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tugas = $_POST['id_tugas'];
    $id_user = $_SESSION['id_user'];
    $nama_berkas = trim(mysqli_real_escape_string($conn, $_POST['nama_berkas']));

    if (!isset($_FILES['file_berkas']) || $_FILES['file_berkas']['error'] != 0) {
        die("Error saat upload file.");
    }

    $file_tmp = $_FILES['file_berkas']['tmp_name'];
    $file_name = basename($_FILES['file_berkas']['name']);
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Buat nama file unik agar tidak overwrite
    $new_file_name = uniqid() . '.' . $ext;
    $upload_dir = 'berkas/';
    $upload_path = $upload_dir . $new_file_name;

    if (!move_uploaded_file($file_tmp, $upload_path)) {
        die("Gagal menyimpan file.");
    }

    // Simpan data berkas ke database
    // Jika sudah ada berkas untuk tugas ini, bisa update atau hapus dulu 
    $cek_berkas = mysqli_query($conn, "SELECT * FROM berkas WHERE id_tugas = $id_tugas");
    if (mysqli_num_rows($cek_berkas) > 0) {
        // Update berkas
        $update = mysqli_query($conn, "UPDATE berkas SET nama_file='$new_file_name', nama_berkas='$nama_berkas' WHERE id_tugas = $id_tugas");
    } else {
        // Insert berkas baru
        $insert = mysqli_query($conn, "INSERT INTO berkas (id_user, id_tugas, nama_file, nama_berkas) VALUES ('$id_user', '$id_tugas', '$new_file_name', '$nama_berkas')");
    }

    header("Location: rinciantugas.php?id=$id_tugas");
    exit;
} else {
    header("Location: daftartugas.php");
    exit;
}