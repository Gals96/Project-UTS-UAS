<?php
include 'db.php';

function formatTanggalIndo($tanggal) {
    $bulanIndo = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei',
                  6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                  10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
    
    $tanggal = explode(' ', $tanggal)[0];
    $pecah = explode('-', $tanggal); // format: YYYY-MM-DD
    $tahun = $pecah[0];
    $bulan = (int)$pecah[1];
    $hari = $pecah[2];
    return $hari . ' ' . $bulanIndo[$bulan] . ' ' . $tahun;
}

// Fungsi membuat slug dari judul
function slugify($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text); // Ganti spasi & simbol jadi strip
    return trim($text, '-');
}
?>
