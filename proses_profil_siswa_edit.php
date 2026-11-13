<?php
require 'koneksi.php';
if(!isset($_SESSION['siswa_id'])){header('Location:siswa_login.php');exit;}
$id=(int)$_SESSION['siswa_id'];
$nama=bersihkan($koneksi,$_POST['nama']??'');$jk=bersihkan($koneksi,$_POST['jenis_kelamin']??'');
$id_kelas=(int)($_POST['id_kelas']??0);$id_jurusan=(int)($_POST['id_jurusan']??0);
$no_hp=bersihkan($koneksi,$_POST['no_hp']??'');$email=bersihkan($koneksi,$_POST['email']??'');$alamat=bersihkan($koneksi,$_POST['alamat']??'');
if($nama===''||$jk===''||$id_kelas<=0||$id_jurusan<=0||$no_hp===''||$email===''||$alamat===''){header('Location:profil_siswa_edit.php?error='.urlencode('Semua data profil wajib diisi.'));exit;}
$set="nama='$nama',jenis_kelamin='$jk',id_kelas=$id_kelas,id_jurusan=$id_jurusan,no_hp='$no_hp',email='$email',alamat='$alamat'";
if(!empty($_POST['password'])){$pw=bersihkan($koneksi,$_POST['password']);$set.=",password='$pw'";}
if(isset($_FILES['foto'])&&$_FILES['foto']['error']===UPLOAD_ERR_OK){
 $ext=strtolower(pathinfo($_FILES['foto']['name'],PATHINFO_EXTENSION));$ok=['jpg','jpeg','png','webp'];
 if(!in_array($ext,$ok,true)){header('Location:profil_siswa_edit.php?error='.urlencode('Format foto harus JPG, JPEG, PNG, atau WEBP.'));exit;}
 $name='anggota_'.$id.'_'.time().'.'.$ext;$dir=__DIR__.'/assets/uploads/anggota/';if(!is_dir($dir))mkdir($dir,0775,true);
 if(!move_uploaded_file($_FILES['foto']['tmp_name'],$dir.$name)){$name='';}
 if($name!=='')$set.=",foto='$name'";
}
if(mysqli_query($koneksi,"UPDATE anggota SET $set WHERE id_anggota=$id"))header('Location:profil_siswa_edit.php?sukses='.urlencode('Profil berhasil diperbarui.'));
else header('Location:profil_siswa_edit.php?error='.urlencode(mysqli_error($koneksi)));
exit;
