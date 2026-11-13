<?php
require 'koneksi.php';
if(!isset($_SESSION['siswa_id'])){header('Location:siswa_login.php');exit;}
$id=(int)$_SESSION['siswa_id'];
$a=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * FROM anggota WHERE id_anggota=$id"));
if(!$a){session_destroy();header('Location:siswa_login.php');exit;}
$kelas=ambil_referensi($koneksi,'kelas','id_kelas','nama_kelas');
$jurusan=ambil_referensi($koneksi,'jurusan','id_jurusan','nama_jurusan');
$error=$_GET['error']??'';$ok=$_GET['sukses']??'';
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit Profil Siswa</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body><?php include 'includes/navbar_public.php';?><div class="page" style="max-width:900px;margin:auto"><h2>✏️ Edit Profil Siswa</h2><p class="muted">Lengkapi semua data profil. Kolom bertanda <span class="required-mark">*</span> wajib diisi dan tidak dapat disimpan jika kosong.</p>
<?php if($error):?><div class="alert-danger">⚠️ <?=htmlspecialchars($error)?></div><?php endif;?><?php if($ok):?><div class="alert-success">✅ <?=htmlspecialchars($ok)?></div><?php endif;?>
<div class="card"><form method="post" action="proses_profil_siswa_edit.php" enctype="multipart/form-data" id="profilSiswaForm" novalidate>
<div class="form-row"><div class="form-group"><label>NISN <span class="required-mark">*</span></label><input class="form-control" value="<?=htmlspecialchars($a['nisn'])?>" readonly></div><div class="form-group"><label>Nama Lengkap <span class="required-mark">*</span></label><input name="nama" class="form-control" value="<?=htmlspecialchars($a['nama'])?>" required></div></div>
<div class="form-row"><div class="form-group"><label>Gender <span class="required-mark">*</span></label><select name="jenis_kelamin" class="form-control" required><option value="">Pilih</option><option value="Laki-laki" <?=$a['jenis_kelamin']==='Laki-laki'?'selected':''?>>Laki-laki</option><option value="Perempuan" <?=$a['jenis_kelamin']==='Perempuan'?'selected':''?>>Perempuan</option></select></div><div class="form-group"><label>Kelas <span class="required-mark">*</span></label><select name="id_kelas" class="form-control" required><option value="">Pilih</option><?php foreach($kelas as $k=>$n):?><option value="<?=$k?>" <?=$a['id_kelas']==$k?'selected':''?>><?=htmlspecialchars($n)?></option><?php endforeach;?></select></div></div>
<div class="form-group"><label>Jurusan <span class="required-mark">*</span></label><select name="id_jurusan" class="form-control" required><option value="">Pilih</option><?php foreach($jurusan as $j=>$n):?><option value="<?=$j?>" <?=$a['id_jurusan']==$j?'selected':''?>><?=htmlspecialchars($n)?></option><?php endforeach;?></select></div>
<div class="form-row"><div class="form-group"><label>No. Telepon <span class="required-mark">*</span></label><input name="no_hp" class="form-control" value="<?=htmlspecialchars($a['no_hp']??'')?>" required></div><div class="form-group"><label>Email <span class="required-mark">*</span></label><input type="email" name="email" class="form-control" value="<?=htmlspecialchars($a['email']??'')?>" required></div></div>
<div class="form-group"><label>Alamat <span class="required-mark">*</span></label><input name="alamat" class="form-control" value="<?=htmlspecialchars($a['alamat']??'')?>" required></div>
<div class="form-group"><label>Foto Anggota</label><input type="file" name="foto" class="form-control" accept="image/*"></div>
<div class="form-group"><label>Password Baru <small>(opsional)</small></label><div class="password-wrap"><input id="siswaEditPassword" type="password" name="password" class="form-control"><button type="button" class="toggle-password" onclick="togglePassword('siswaEditPassword',this)">👁</button></div></div>
<button class="btn btn-success" type="submit">💾 Simpan Perubahan</button> <a class="btn btn-secondary" href="profil_siswa.php">Batal</a>
</form></div></div>
<script>document.getElementById('profilSiswaForm').addEventListener('submit',function(e){if(!this.checkValidity()){e.preventDefault();this.reportValidity();}});function togglePassword(id,btn){const i=document.getElementById(id);if(i.type==='password'){i.type='text';btn.textContent='🙈';}else{i.type='password';btn.textContent='👁';}}</script>
</body></html>
