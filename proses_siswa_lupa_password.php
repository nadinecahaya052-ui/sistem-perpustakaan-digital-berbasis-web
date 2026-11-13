<?php
require 'koneksi.php';
$id = bersihkan($koneksi, $_POST['identitas'] ?? '');
$pwRaw = $_POST['password'] ?? '';
$konfirmasiRaw = $_POST['konfirmasi'] ?? '';
$pw = bersihkan($koneksi, $pwRaw);
$konfirmasi = bersihkan($koneksi, $konfirmasiRaw);

if ($id === '' || $pwRaw === '' || $konfirmasiRaw === '') {
    header('Location: siswa_lupa_password.php?err=' . urlencode('NISN, password baru, dan konfirmasi password wajib diisi.'));
    exit;
}
if (strlen($pwRaw) < 6) {
    header('Location: siswa_lupa_password.php?err=' . urlencode('Password baru minimal 6 karakter.'));
    exit;
}
if ($pwRaw !== $konfirmasiRaw) {
    header('Location: siswa_lupa_password.php?err=' . urlencode('Konfirmasi password baru tidak cocok.'));
    exit;
}

$cek = mysqli_query($koneksi, "SELECT nisn FROM anggota WHERE nisn='$id' LIMIT 1");
if (!$cek || mysqli_num_rows($cek) === 0) {
    header('Location: siswa_lupa_password.php?err=' . urlencode('Data siswa tidak ditemukan.'));
    exit;
}

if (!mysqli_query($koneksi, "UPDATE anggota SET password='$pw' WHERE nisn='$id'")) {
    header('Location: siswa_lupa_password.php?err=' . urlencode('Gagal mengubah password.'));
    exit;
}
header('Location: siswa_lupa_password.php?msg=' . urlencode('Password berhasil diubah. Silakan login kembali.'));
exit;
