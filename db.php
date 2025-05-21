<?php
$conn = mysqli_connect("localhost", "root", "", "tugasku");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>