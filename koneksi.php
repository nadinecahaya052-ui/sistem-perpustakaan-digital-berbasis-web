<?php
/**
 * koneksi.php
 * Koneksi database MySQL + fungsi bantu yang dipakai di seluruh aplikasi
 * (halaman publik maupun panel admin).
 */

session_start();

$host   = "localhost";
$user   = "root";
$pass   = "";
$dbname = "perpustakaan_digital_kelompok3";

$koneksi = mysqli_connect($host, $user, $pass, $dbname);
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
mysqli_set_charset($koneksi, "utf8mb4");

function bersihkan($koneksi, $data)
{
    $data = trim($data ?? "");
    $data = stripslashes($data);
    $data = mysqli_real_escape_string($koneksi, $data);
    return $data;
}

// Aturan peminjaman (boleh diubah sesuai kebutuhan)
define("BATAS_HARI_PINJAM", 7);
define("DENDA_PER_HARI", 1000);


function rupiah($n){ return 'Rp'.number_format((int)$n,0,',','.'); }
function status_badge($status){
    $class = $status === 'Dikembalikan' ? 'badge-lunas' : 'badge-belum';
    return '<span class="badge '.$class.'">'.htmlspecialchars($status).'</span>';
}

/** Ambil semua baris dari sebuah tabel referensi sebagai array id => nama, untuk dropdown */
function ambil_referensi($koneksi, $tabel, $kolom_id, $kolom_nama)
{
    $data = [];
    $q = mysqli_query($koneksi, "SELECT $kolom_id, $kolom_nama FROM $tabel ORDER BY $kolom_nama ASC");
    while ($r = mysqli_fetch_assoc($q)) {
        $data[$r[$kolom_id]] = $r[$kolom_nama];
    }
    return $data;
}
