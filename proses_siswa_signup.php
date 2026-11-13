<?php
require "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: siswa_signup.php"); exit; }

$_SESSION['old_signup'] = $_POST; // simpan sementara untuk isi ulang form jika gagal

$nisn        = bersihkan($koneksi, $_POST['nisn']);
$nama        = bersihkan($koneksi, $_POST['nama']);
$password    = bersihkan($koneksi, $_POST['password']);
$konfirmasi  = bersihkan($koneksi, $_POST['konfirmasi']);
$jk          = bersihkan($koneksi, $_POST['jenis_kelamin']);
$id_kelas    = (int) ($_POST['id_kelas'] ?: 0);
$id_jurusan  = (int) ($_POST['id_jurusan'] ?: 0);
$no_hp       = bersihkan($koneksi, $_POST['no_hp']);
$tgl_daftar  = bersihkan($koneksi, $_POST['tgl_daftar']);

if ($nisn == "" || $nama == "" || $password == "" || $konfirmasi == "" || $jk == "" || $id_kelas <= 0 || $id_jurusan <= 0 || $no_hp == "" || $tgl_daftar == "") {
    header("Location: siswa_signup.php?error=" . urlencode("Semua data pendaftaran wajib diisi."));
    exit;
}
if ($password !== $konfirmasi) {
    header("Location: siswa_signup.php?error=" . urlencode("Confirm Password tidak cocok."));
    exit;
}

$cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_anggota FROM anggota WHERE nisn='$nisn'"));
if ($cek) {
    header("Location: siswa_signup.php?error=" . urlencode("NISN sudah terdaftar, silahkan login."));
    exit;
}


$id_kelas_sql   = $id_kelas > 0 ? $id_kelas : "NULL";
$id_jurusan_sql = $id_jurusan > 0 ? $id_jurusan : "NULL";
$tgl_daftar_sql = $tgl_daftar != "" ? "'$tgl_daftar'" : "CURDATE()";

$q = "INSERT INTO anggota (nisn, password, nama, id_kelas, id_jurusan, jenis_kelamin, no_hp, tgl_daftar)
      VALUES ('$nisn', '$password', '$nama', $id_kelas_sql, $id_jurusan_sql, '$jk', '$no_hp', $tgl_daftar_sql)";

if (mysqli_query($koneksi, $q)) {
    unset($_SESSION['old_signup']);
    header("Location: siswa_login.php?sukses=" . urlencode("Pendaftaran berhasil! Silahkan login."));
} else {
    header("Location: siswa_signup.php?error=" . urlencode("Gagal mendaftar: " . mysqli_error($koneksi)));
}
exit;
