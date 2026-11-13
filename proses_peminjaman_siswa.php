<?php
require 'koneksi.php';
if (!isset($_SESSION['siswa_id'])) { header('Location:siswa_login.php'); exit; }
$id_siswa=(int)$_SESSION['siswa_id'];
$ids=$_POST['id_buku']??[]; $ids=array_values(array_unique(array_map('intval',(array)$ids)));
if (!$ids) { header('Location:peminjaman_siswa.php?error='.urlencode('Pilih minimal satu buku.')); exit; }
$aktif=(int)(mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) n FROM detail_peminjaman d JOIN peminjaman p ON p.id_peminjaman=d.id_peminjaman WHERE p.id_anggota=$id_siswa AND d.status='Dipinjam'"))['n']??0);
if ($aktif+count($ids)>3) { header('Location:peminjaman_siswa.php?error='.urlencode('Maksimal 3 buku aktif dapat dipinjam.')); exit; }
mysqli_begin_transaction($koneksi);
try {
  $tgl=bersihkan($koneksi,$_POST['tgl_pinjam']??'');
  $batas=bersihkan($koneksi,$_POST['tgl_batas_kembali']??'');
  $today=date('Y-m-d');
  $maxBatas=date('Y-m-d',strtotime('+'.BATAS_HARI_PINJAM.' days'));
  if($tgl!==$today) throw new Exception('Tanggal peminjaman harus tanggal hari ini.');
  if($batas==='' || $batas<$tgl || $batas>$maxBatas) throw new Exception('Batas pengembalian harus dipilih melalui kalender dan maksimal '.BATAS_HARI_PINJAM.' hari.');
  $header=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT id_peminjaman FROM peminjaman WHERE id_anggota=$id_siswa AND tgl_pinjam='$tgl' ORDER BY id_peminjaman DESC LIMIT 1"));
  if($header){$idp=(int)$header['id_peminjaman']; mysqli_query($koneksi,"UPDATE peminjaman SET tgl_jatuh_tempo='$batas' WHERE id_peminjaman=$idp");} else { if(!mysqli_query($koneksi,"INSERT INTO peminjaman(id_anggota,tgl_pinjam,tgl_jatuh_tempo) VALUES($id_siswa,'$tgl','$batas')")) throw new Exception('Gagal membuat transaksi.'); $idp=mysqli_insert_id($koneksi); }
  foreach($ids as $idb){
    $b=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT id_buku,stok FROM buku WHERE id_buku=$idb FOR UPDATE"));
    if(!$b || (int)$b['stok']<1) throw new Exception('Ada buku yang stoknya sudah habis.');
    $cek=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT id_detail FROM detail_peminjaman WHERE id_peminjaman=$idp AND id_buku=$idb AND status='Dipinjam' LIMIT 1"));
    if($cek) throw new Exception('Buku yang sama sudah ada dalam transaksi.');
    if(!mysqli_query($koneksi,"INSERT INTO detail_peminjaman(id_peminjaman,id_buku,jumlah,tgl_batas_kembali,status,denda) VALUES($idp,$idb,1,'$batas','Dipinjam',0)")) throw new Exception('Gagal menyimpan detail peminjaman.');
    if(!mysqli_query($koneksi,"UPDATE buku SET stok=stok-1, stok_tersedia=stok_tersedia-1, status=CASE WHEN stok_tersedia-1<=0 THEN 'Habis' ELSE 'Tersedia' END WHERE id_buku=$idb AND stok_tersedia>0")) throw new Exception('Gagal mengurangi stok buku.');
  }
  mysqli_commit($koneksi); header('Location:riwayat-peminjaman.php?sukses='.urlencode('Peminjaman berhasil disimpan. Batas pengembalian '.$batas.'.')); exit;
} catch(Throwable $e){ mysqli_rollback($koneksi); header('Location:peminjaman_siswa.php?error='.urlencode($e->getMessage())); exit; }
