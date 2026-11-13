<?php
require 'koneksi.php';
if(!isset($_SESSION['siswa_id'])){header('Location:siswa_login.php');exit;}
$id=(int)$_SESSION['siswa_id'];
$q=mysqli_query($koneksi,"SELECT foto FROM anggota WHERE id_anggota=$id LIMIT 1");
$a=mysqli_fetch_assoc($q);
if(!$a){header('Location:siswa_login.php');exit;}
$foto=trim((string)($a['foto']??''));
if($foto!=='' && strtolower($foto)!=='default.png'){
  $path=__DIR__.'/assets/uploads/anggota/'.$foto;
  if(is_file($path)){@unlink($path);}
}
if(mysqli_query($koneksi,"UPDATE anggota SET foto='default.png' WHERE id_anggota=$id")){
  header('Location:profil_siswa.php?tipe=success&pesan='.urlencode('Foto profil berhasil dihapus dan dikembalikan ke foto default.'));
}else{
  header('Location:profil_siswa.php?tipe=danger&pesan='.urlencode('Foto gagal dihapus: '.mysqli_error($koneksi)));
}
exit;
