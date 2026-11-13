<?php
require 'koneksi.php';
if(!isset($_SESSION['siswa_id'])){header('Location:siswa_login.php');exit;}
$id=(int)$_SESSION['siswa_id'];
$a=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT a.*,k.nama_kelas,j.nama_jurusan FROM anggota a LEFT JOIN kelas k ON k.id_kelas=a.id_kelas LEFT JOIN jurusan j ON j.id_jurusan=a.id_jurusan WHERE a.id_anggota=$id"));
if(!$a){session_destroy();header('Location:siswa_login.php');exit;}
$total=(int)(mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) n FROM peminjaman WHERE id_anggota=$id"))['n']??0);
$wajib=['nama','jenis_kelamin','id_kelas','id_jurusan','no_hp','email','alamat'];
$lengkap=true; foreach($wajib as $f){ if(trim((string)($a[$f]??''))===''){ $lengkap=false; break; } }
$foto=trim((string)($a['foto']??''));
$punyaFoto=$foto!=='' && strtolower($foto)!=='default.png';
$fotoSrc=$punyaFoto?'assets/uploads/anggota/'.rawurlencode($foto):'default.png';
$pesan=$_GET['pesan']??''; $tipe=$_GET['tipe']??'success';
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Profil Siswa</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body><?php include 'includes/navbar_public.php';?>
<div class="page" style="max-width:1050px;margin:auto">
  <div class="card-header" style="margin-bottom:18px"><div><h2>👤 Profil Siswa</h2><p class="muted">Data pribadi akun siswa. Kolom bertanda <span class="required-mark">*</span> wajib dilengkapi.</p></div><div class="profile-actions">
    <?php if(!$lengkap): ?><a class="btn btn-warning" href="profil_siswa_edit.php">➕ Lengkapi Profil</a><?php endif; ?>
    <a class="btn btn-primary" href="profil_siswa_edit.php">✏️ Edit Profil</a> <a class="btn btn-success" href="kartu_saya.php">🪪 Cetak Kartu Anggota</a>
    <?php if($punyaFoto): ?><form method="post" action="proses_hapus_foto_siswa.php" onsubmit="return confirm('Hapus foto profil ini? Foto akan dikembalikan ke foto default.');" style="display:inline"><button class="btn btn-danger" type="submit">🗑️ Hapus Foto</button></form><?php endif; ?>
  </div></div>
  <?php if($pesan): ?><div class="alert-<?= $tipe==='danger'?'danger':'success' ?>"><?=htmlspecialchars($pesan)?></div><?php endif; ?>
  <div class="card profile-card">
    <div class="profile-photo-panel"><img class="avatar-profil" src="<?=htmlspecialchars($fotoSrc)?>" onerror="this.src='default.png'" alt="Foto profil siswa"><span class="profile-photo-label">Foto Profil</span></div>
    <div class="profile-data">
      <h3><?=htmlspecialchars($a['nama'])?><?php if(!$lengkap): ?><span class="badge badge-belum" style="margin-left:8px">Profil belum lengkap</span><?php endif; ?></h3>
      <div class="profile-grid">
        <div><span>NISN <b>*</b></span><strong><?=htmlspecialchars($a['nisn']??'-')?></strong></div>
        <div><span>Kelas <b>*</b></span><strong><?=htmlspecialchars($a['nama_kelas']??'-')?></strong></div>
        <div><span>Jurusan <b>*</b></span><strong><?=htmlspecialchars($a['nama_jurusan']??'-')?></strong></div>
        <div><span>Jenis Kelamin <b>*</b></span><strong><?=htmlspecialchars($a['jenis_kelamin']??'-')?></strong></div>
        <div><span>No. Telepon <b>*</b></span><strong><?=htmlspecialchars($a['no_hp']??'-')?></strong></div>
        <div><span>Email <b>*</b></span><strong><?=htmlspecialchars($a['email']??'-')?></strong></div>
        <div><span>Tanggal Daftar</span><strong><?=htmlspecialchars($a['tgl_daftar']??'-')?></strong></div>
        <div class="profile-wide"><span>Alamat <b>*</b></span><strong><?=htmlspecialchars($a['alamat']??'-')?></strong></div>
      </div>
      <div class="profile-bottom"><span class="badge badge-stok">Total Pinjaman: <?=$total?></span><a class="btn btn-primary" href="riwayat-peminjaman.php">📚 Riwayat Peminjaman</a></div>
    </div>
  </div>
  <div class="card"><div class="card-header"><h3>Kelola Profil</h3></div><p class="muted" style="margin-bottom:14px">Kamu dapat mengubah data pribadi, menambah data yang masih kosong, mengganti password, mengganti foto, atau menghapus foto profil.</p><a class="btn btn-primary" href="profil_siswa_edit.php">✏️ Ubah / Tambah Data Profil</a> <?php if($punyaFoto): ?><form method="post" action="proses_hapus_foto_siswa.php" style="display:inline" onsubmit="return confirm('Yakin hapus foto profil?');"><button class="btn btn-danger" type="submit">🗑️ Hapus Foto</button></form><?php endif; ?></div>
</div>
</body></html>
