<?php
require 'koneksi.php';
if (!isset($_SESSION['siswa_id'])) { header('Location:siswa_login.php'); exit; }
$id_siswa = (int)$_SESSION['siswa_id'];
$siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT a.*, k.nama_kelas FROM anggota a LEFT JOIN kelas k ON k.id_kelas=a.id_kelas WHERE a.id_anggota=$id_siswa"));
if (!$siswa) { session_destroy(); header('Location:siswa_login.php'); exit; }
$q = trim($_GET['q'] ?? '');
$x = $q !== '' ? bersihkan($koneksi,$q) : '';
$kat = (int)($_GET['kategori'] ?? 0);
$urut = $_GET['urut'] ?? 'judul'; if (!in_array($urut,['judul','stok'],true)) $urut = 'judul';
$w = ['b.stok>0'];
if ($x !== '') $w[] = "(b.judul LIKE '%$x%' OR b.pengarang LIKE '%$x%' OR b.isbn LIKE '%$x%' OR k.nama_kategori LIKE '%$x%')";
if ($kat > 0) $w[] = "b.id_kategori=$kat";
$order = $urut === 'stok' ? 'b.stok DESC, b.judul ASC' : 'b.judul ASC';
$katList = mysqli_query($koneksi, "SELECT id_kategori,nama_kategori FROM kategori_buku ORDER BY nama_kategori");
$buku = mysqli_query($koneksi, "SELECT b.id_buku,b.judul,b.pengarang,b.stok,b.cover,k.nama_kategori FROM buku b LEFT JOIN kategori_buku k ON k.id_kategori=b.id_kategori WHERE ".implode(' AND ',$w)." ORDER BY $order LIMIT 50");
$aktif = (int)(mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) n FROM detail_peminjaman d JOIN peminjaman p ON p.id_peminjaman=d.id_peminjaman WHERE p.id_anggota=$id_siswa AND d.status='Dipinjam'"))['n']??0);
$max = 3; $sisa = max(0,$max-$aktif); $error=$_GET['error']??''; $ok=$_GET['sukses']??'';
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Peminjaman Buku | Perpustakaan Digital SMA NEGERI 1 KAMAL</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<?php include 'includes/navbar_public.php'; ?>
<div class="page" style="max-width:1200px;margin:auto">
<h2>📖 Form Peminjaman Buku</h2><p class="muted">Isi data peminjaman buku dengan lengkap dan benar.</p>
<?php if($error):?><div class="alert-danger">⚠️ <?=htmlspecialchars($error)?></div><?php endif;?><?php if($ok):?><div class="alert-success">✅ <?=htmlspecialchars($ok)?></div><?php endif;?>
<div class="form-row" style="align-items:flex-start">
<div class="card" style="flex:1;min-width:320px"><h3>Data Peminjam</h3>
<div class="form-group"><label>NISN</label><input class="form-control" value="<?=htmlspecialchars($siswa['nisn'])?>" readonly></div>
<div class="form-group"><label>Nama Anggota</label><input class="form-control" value="<?=htmlspecialchars($siswa['nama'])?>" readonly></div>
<div class="form-group"><label>Kelas</label><input class="form-control" value="<?=htmlspecialchars($siswa['nama_kelas']??'-')?>" readonly></div>
<div class="alert-info">Maksimal <?= $max ?> eksemplar aktif. Anda masih dapat meminjam <b><?= $sisa ?></b> buku.</div>
</div>
<div class="card" style="flex:1.6;min-width:420px"><h3>📚 Pilih Buku</h3>
<form method="get" class="search-box" style="margin-bottom:12px;flex-wrap:wrap;gap:8px"><input class="form-control" name="q" value="<?=htmlspecialchars($q)?>" placeholder="Cari judul buku, penulis, ISBN, kategori..."><select class="form-control" name="kategori" onchange="this.form.submit()"><option value="0">Semua kategori</option><?php while($kc=mysqli_fetch_assoc($katList)):?><option value="<?=$kc['id_kategori']?>" <?=$kat==$kc['id_kategori']?'selected':''?>><?=htmlspecialchars($kc['nama_kategori'])?></option><?php endwhile;?></select><select class="form-control" name="urut" onchange="this.form.submit()"><option value="judul" <?=$urut==='judul'?'selected':''?>>Judul A–Z</option><option value="stok" <?=$urut==='stok'?'selected':''?>>Stok terbanyak</option></select><button class="btn btn-primary">Cari</button><a class="btn btn-secondary" href="peminjaman_siswa.php">Reset</a></form>
<form method="post" action="proses_peminjaman_siswa.php" id="formPinjam">
<div class="table-wrap"><table class="data-table"><tr><th>Pilih</th><th>Cover</th><th>Judul Buku</th><th>Penulis</th><th>Stok</th></tr>
<?php while($b=mysqli_fetch_assoc($buku)):?><tr><td><input type="checkbox" name="id_buku[]" value="<?=$b['id_buku']?>" data-max="<?=$b['stok']?>"></td><td><img class="cover-kecil" src="assets/uploads/buku/<?=htmlspecialchars($b['cover'])?>" onerror="this.src='default.png'"></td><td><b><?=htmlspecialchars($b['judul'])?></b><br><small><?=htmlspecialchars($b['nama_kategori']??'-')?></small></td><td><?=htmlspecialchars($b['pengarang'])?></td><td><?=$b['stok']?></td></tr><?php endwhile;?>
</table></div>
<div class="form-row">
<div class="form-group">
<label>📅 Tanggal Peminjaman <span class="required-mark">*</span></label>
<input type="date" class="form-control" name="tgl_pinjam" value="<?=date('Y-m-d')?>" min="<?=date('Y-m-d')?>" max="<?=date('Y-m-d')?>" required>
</div>
<div class="form-group">
<label>📅 Batas Pengembalian <span class="required-mark">*</span></label>
<input type="date" class="form-control" name="tgl_batas_kembali" value="<?=date('Y-m-d',strtotime('+'.BATAS_HARI_PINJAM.' days'))?>" min="<?=date('Y-m-d',strtotime('+1 day'))?>" max="<?=date('Y-m-d',strtotime('+'.BATAS_HARI_PINJAM.' days'))?>" required>
</div></div>
<p class="muted">Jangka waktu peminjaman: <?=BATAS_HARI_PINJAM?> hari. Denda keterlambatan: <?=rupiah(DENDA_PER_HARI)?>/hari.</p>
<button class="btn btn-success" type="submit" <?= $sisa<1?'disabled':'' ?>>💾 Simpan Peminjaman</button> <a class="btn btn-secondary" href="riwayat-peminjaman.php">Riwayat</a>
</form></div></div></div>
<script>document.getElementById('formPinjam').addEventListener('submit',function(e){const c=[...this.querySelectorAll('input[name="id_buku[]"]:checked')];const max=<?=$sisa?>;if(c.length<1){e.preventDefault();alert('Pilih minimal 1 buku.')}else if(c.length>max){e.preventDefault();alert('Maksimal '+max+' buku sesuai sisa kuota peminjaman Anda.');}});
const tglPinjam=document.querySelector('input[name="tgl_pinjam"]');
const tglBatas=document.querySelector('input[name="tgl_batas_kembali"]');
function sinkronTanggal(){
  if(!tglPinjam||!tglBatas)return;
  const d=new Date(tglPinjam.value+'T00:00:00');
  if(Number.isNaN(d.getTime())) return;
  const max=new Date(d); max.setDate(max.getDate()+<?=BATAS_HARI_PINJAM?>);
  const iso=max.toISOString().slice(0,10);
  tglBatas.min=tglPinjam.value; tglBatas.max=iso;
  if(tglBatas.value<tglBatas.min || tglBatas.value>tglBatas.max) tglBatas.value=iso;
}
tglPinjam&&tglPinjam.addEventListener('change',sinkronTanggal); sinkronTanggal();
</script>
</body></html>
