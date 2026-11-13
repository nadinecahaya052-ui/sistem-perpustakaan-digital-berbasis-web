<?php
require "koneksi.php";
$halaman_aktif_publik = 'koleksi';

$per_halaman = 10;
$halaman = isset($_GET['halaman']) ? max(1, (int)$_GET['halaman']) : 1;
$offset = ($halaman - 1) * $per_halaman;

$total_data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) n FROM buku"))["n"];
$total_halaman = max(1, ceil($total_data / $per_halaman));
$data_buku = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id_buku DESC LIMIT $per_halaman OFFSET $offset");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Koleksi Buku | Smart Perpus</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include "includes/navbar_public.php"; ?>
<div class="hero"><h1>Koleksi Buku</h1><p>Total <?= $total_data ?> judul buku tersedia di perpustakaan kami.</p></div>

<h3 class="section-title">📚 Semua Buku</h3>
<div class="buku-grid">
    <?php while ($b = mysqli_fetch_assoc($data_buku)): ?>
    <div class="buku-card">
        <div class="cover-wrap">
            <div class="info-btn" onclick="bukaDetail(<?= $b['id_buku'] ?>)">i</div>
            <img class="cover" src="assets/uploads/buku/<?= htmlspecialchars($b['cover']) ?>" onerror="this.src='default.png'">
        </div>
        <div class="body">
            <div class="judul"><?= htmlspecialchars($b['judul']) ?></div>
            <div class="meta">👤 <?= htmlspecialchars($b['pengarang']) ?></div>
            <div class="meta">📅 <?= htmlspecialchars($b['tahun_terbit']) ?></div>
            <div class="badges">
                <span class="badge badge-stok">Stok: <?= $b['stok'] ?></span>
                <span class="badge badge-rak"><?= htmlspecialchars($b['rak']) ?></span>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<div class="pagination">
    <?php for ($p=1; $p<=$total_halaman; $p++): ?>
        <a class="<?= $p==$halaman?'active':'' ?>" href="koleksi.php?halaman=<?= $p ?>"><?= $p ?></a>
    <?php endfor; ?>
</div>

<div class="modal-bg" id="modalDetail"><div class="modal-box" id="isiModal">Memuat...</div></div>
<footer class="site-footer">2026 &copy; Smart Perpus - Perpustakaan Online</footer>
<script>
function bukaDetail(id){document.getElementById('modalDetail').classList.add('show');fetch('detail_buku.php?id='+id).then(r=>r.text()).then(h=>document.getElementById('isiModal').innerHTML=h);}
function tutupDetail(){document.getElementById('modalDetail').classList.remove('show');}
document.getElementById('modalDetail').addEventListener('click',function(e){if(e.target===this)tutupDetail();});
</script>
</body>
</html>
