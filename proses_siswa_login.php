<?php
require "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: siswa_login.php"); exit; }

$identitas = bersihkan($koneksi, $_POST['identitas'] ?? '');
$password  = bersihkan($koneksi, $_POST['password'] ?? '');
if ($identitas === '' || $password === '') { header('Location: siswa_login.php?error='.urlencode('NISN dan password wajib diisi.')); exit; }

$q = mysqli_query($koneksi, "
    SELECT a.*, k.nama_kelas FROM anggota a LEFT JOIN kelas k ON k.id_kelas=a.id_kelas
    WHERE a.nisn = '$identitas'
");
$siswa = mysqli_fetch_assoc($q);

if ($siswa && $siswa['password'] !== null && $siswa['password'] === $password) {
    $_SESSION['siswa_id']    = $siswa['id_anggota'];
    $_SESSION['siswa_nama']  = $siswa['nama'];
    $_SESSION['siswa_kelas'] = $siswa['nama_kelas'];
    header("Location: index.php?sukses=" . urlencode("Selamat datang kembali, {$siswa['nama']}!"));
} else {
    header("Location: siswa_login.php?error=" . urlencode("NISN atau password salah."));
}
exit;
