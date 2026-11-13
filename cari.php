<?php
require "koneksi.php";
$halaman_aktif_publik='cari';
$q=trim($_GET['q']??''); $penulis=trim($_GET['penulis']??''); $id_kategori=(int)($_GET['kategori']??0); $id_jenis=(int)($_GET['jenis']??0); $urut=$_GET['urut']??'terbaru';
$per_halaman=8; $halaman=max(1,(int)($_GET['halaman']??1)); $offset=($halaman-1)*$per_halaman;
$w='WHERE 1=1'; $params=[];
if($q!==''){ $x=bersihkan($koneksi,$q); $w.=" AND (b.judul LIKE '%$x%' OR b.pengarang LIKE '%$x%' OR b.penerbit LIKE '%$x%' OR b.isbn LIKE '%$x%' OR k.nama_kategori LIKE '%$x%' OR j.nama_jenis LIKE '%$x%' OR d.kode_ddc LIKE '%$x%')"; }
if($penulis!==''){ $x=bersihkan($koneksi,$penulis); $w.=" AND b.pengarang LIKE '%$x%'"; }
if($id_kategori>0)$w.=" AND b.id_kategori=$id_kategori"; if($id_jenis>0)$w.=" AND b.id_jenis=$id_jenis";
$urut=in_array($urut,['terbaru','judul','stok'],true)?$urut:'terbaru'; $order=['terbaru'=>'b.created_at DESC,b.id_buku DESC','judul'=>'b.judul ASC','stok'=>'b.stok DESC,b.judul ASC'][$urut];
$total=(int)(mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) n FROM buku b LEFT JOIN kategori_buku k ON k.id_kategori=b.id_kategori LEFT JOIN jenis_buku j ON j.id_jenis=b.id_jenis LEFT JOIN klasifikasi_ddc d ON d.id_ddc=b.id_ddc $w"))['n']??0);
$pages=max(1,(int)ceil($total/$per_halaman));
$data=mysqli_query($koneksi,"SELECT b.*,k.nama_kategori,j.nama_jenis,d.kode_ddc FROM buku b LEFT JOIN kategori_buku k ON k.id_kategori=b.id_kategori LEFT JOIN jenis_buku j ON j.id_jenis=b.id_jenis LEFT JOIN klasifikasi_ddc d ON d.id_ddc=b.id_ddc $w ORDER BY $order LIMIT $per_halaman OFFSET $offset");
$cats=mysqli_query($koneksi,"SELECT * FROM kategori_buku ORDER BY nama_kategori"); $jenis=mysqli_query($koneksi,"SELECT * FROM jenis_buku ORDER BY nama_jenis");
$authors=mysqli_query($koneksi,"SELECT DISTINCT pengarang FROM buku WHERE pengarang<>'' ORDER BY pengarang");
$qs=$_GET; unset($qs['halaman']); $base=http_build_query($qs);
$has_filter=($q!=='' || $penulis!=='' || $id_kategori>0 || $id_jenis>0 || $urut!=='terbaru');
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Cari Buku | Perpustakaan Digital SMA NEGERI 1 KAMAL</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<?php include "includes/navbar_public.php"; ?><div class="hero"><h1>🔎 Cari Buku</h1><p>Cari berdasarkan judul, penulis, kategori, penerbit, ISBN, DDC atau kata kunci.</p></div>
<div class="search-panel" id="katalogFilter"><form method="GET" id="filterForm" autocomplete="off"><div class="filter-grid"><div class="filter-field keyword-field"><label for="q">Judul / kata kunci</label><input id="q" class="form-control" name="q" placeholder="Ketik judul, penulis, ISBN, DDC..." value="<?=htmlspecialchars($q)?>"></div><div class="filter-field"><label for="penulis">Penulis</label><select id="penulis" class="form-control filter-select" name="penulis"><option value="">Semua Penulis</option><?php while($a=mysqli_fetch_assoc($authors)):?><option value="<?=htmlspecialchars($a['pengarang'])?>" <?= $penulis===$a['pengarang']?'selected':'' ?>><?=htmlspecialchars($a['pengarang'])?></option><?php endwhile;?></select></div><div class="filter-field"><label for="kategori">Kategori</label><select id="kategori" class="form-control filter-select" name="kategori"><option value="0">Semua Kategori</option><?php while($c=mysqli_fetch_assoc($cats)):?><option value="<?=$c['id_kategori']?>" <?=$id_kategori==$c['id_kategori']?'selected':''?>><?=htmlspecialchars($c['nama_kategori'])?></option><?php endwhile;?></select></div><div class="filter-field"><label for="jenis">Jenis Buku</label><select id="jenis" class="form-control filter-select" name="jenis"><option value="0">Semua Jenis</option><?php while($j=mysqli_fetch_assoc($jenis)):?><option value="<?=$j['id_jenis']?>" <?=$id_jenis==$j['id_jenis']?'selected':''?>><?=htmlspecialchars($j['nama_jenis'])?></option><?php endwhile;?></select></div><div class="filter-field"><label for="urut">Urutan</label><select id="urut" class="form-control filter-select" name="urut"><option value="terbaru" <?=$urut==='terbaru'?'selected':''?>>Terbaru</option><option value="judul" <?=$urut==='judul'?'selected':''?>>Urutkan judul A-Z</option><option value="stok" <?=$urut==='stok'?'selected':''?>>Stok terbanyak</option></select></div></div><div class="filter-actions"><button type="submit" class="btn btn-primary">🔎 Cari</button><a class="btn btn-secondary" href="cari.php#katalogFilter">↺ Reset</a><span class="filter-hint">Pilih filter → daftar buku di bawah otomatis mengikuti pilihan.</span></div></form><p class="pagination-info">Ditemukan <b><?=$total?></b> buku<?= $has_filter ? ' sesuai filter.' : '.' ?></p></div>
<h3 class="section-title">Daftar Buku</h3><div class="buku-grid"><?php if(mysqli_num_rows($data)===0):?><p>Buku tidak ditemukan.</p><?php endif;?><?php while($b=mysqli_fetch_assoc($data)):?><article class="buku-card" data-book-id="<?=$b['id_buku']?>"><div class="cover-wrap"><button type="button" class="info-btn" onclick="bukaDetail(<?=$b['id_buku']?>)" aria-label="Lihat detail buku">i</button><img class="cover" src="assets/uploads/buku/<?=htmlspecialchars($b['cover'])?>" alt="Sampul <?=htmlspecialchars($b['judul'])?>" onerror="this.src='default.png'"></div><div class="body"><button type="button" class="judul judul-link" onclick="bukaDetail(<?=$b['id_buku']?>)"><?=htmlspecialchars($b['judul'])?></button><div class="meta">👤 <?=htmlspecialchars($b['pengarang'])?></div><div class="meta">🏷️ <?=htmlspecialchars($b['nama_kategori']??'-')?> · <?=htmlspecialchars($b['nama_jenis']??'-')?></div><div class="meta">📅 <?=htmlspecialchars($b['tahun_terbit'])?></div><div class="badges"><span class="badge badge-stok">Stok <?=$b['stok']?></span><span class="badge badge-rak">DDC <?=htmlspecialchars($b['kode_ddc']??'-')?></span></div></div></article><?php endwhile;?></div>
<div class="pagination"><?php for($p=1;$p<=$pages;$p++):?><a class="<?=$p===$halaman?'active':''?>" href="cari.php?<?=$base?>&halaman=<?=$p?>"><?=$p?></a><?php endfor;?></div><div class="modal-bg" id="modalDetail"><div class="modal-box" id="isiModal">Memuat...</div></div><footer class="site-footer">2026 &copy; Perpustakaan Digital SMA NEGERI 1 KAMAL</footer>
<script>
(function(){
  const form=document.getElementById('filterForm');
  const selects=document.querySelectorAll('.filter-select');
  selects.forEach(function(el){
    el.addEventListener('change',function(){
      const page=form.querySelector('input[name="halaman"]'); if(page) page.remove();
      form.submit();
    });
  });
  form.addEventListener('submit',function(){
    const old=form.querySelector('input[name="halaman"]'); if(old) old.remove();
  });
  const q=document.getElementById('q');
  q.addEventListener('keydown',function(e){if(e.key==='Enter'){e.preventDefault();form.submit();}});
})();
function bukaDetail(id){
  const modal=document.getElementById('modalDetail');
  const isi=document.getElementById('isiModal');
  modal.classList.add('show'); isi.innerHTML='<div class="loading-detail">Memuat detail buku...</div>';
  fetch('detail_buku.php?id='+encodeURIComponent(id),{headers:{'X-Requested-With':'XMLHttpRequest'}})
    .then(r=>{if(!r.ok) throw new Error('Gagal memuat detail'); return r.text();})
    .then(h=>isi.innerHTML=h)
    .catch(()=>isi.innerHTML='<p>Detail buku tidak dapat dimuat.</p>');
}
function tutupDetail(){document.getElementById('modalDetail').classList.remove('show')}
document.getElementById('modalDetail').addEventListener('click',e=>{if(e.target===e.currentTarget)tutupDetail()});
document.addEventListener('keydown',e=>{if(e.key==='Escape')tutupDetail()});
</script></body></html>
