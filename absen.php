<?php
require "koneksi.php";
$halaman_aktif_publik = 'absen';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = bersihkan($koneksi, $_POST['nama'] ?? '');
    $keperluan = bersihkan($koneksi, $_POST['keperluan'] ?? '');
    if ($nama === '' || $keperluan === '') {
        header('Location: absen.php?error=' . urlencode('Nama pengunjung dan keperluan wajib diisi.'));
        exit;
    }
    $id_anggota = isset($_SESSION['siswa_id']) ? (int) $_SESSION['siswa_id'] : "NULL";
    mysqli_query($koneksi, "INSERT INTO pengunjung (id_anggota, nama, keperluan, tanggal, jam)
        VALUES (" . (is_numeric($id_anggota) ? $id_anggota : "NULL") . ", '$nama', '$keperluan', CURDATE(), CURTIME())");
    header("Location: absen.php?sukses=1");
    exit;
}

$data = mysqli_query($koneksi, "SELECT * FROM pengunjung WHERE tanggal = CURDATE() ORDER BY id_pengunjung DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Absen Pengunjung | Perpustakaan Digital</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include "includes/navbar_public.php"; ?>
<div class="hero"><h1>Absen Pengunjung</h1></div>

<div class="search-panel">
    <?php if (isset($_GET['sukses'])): ?><div class="alert-success">✅ Terima kasih, absensi kunjungan berhasil dicatat.</div><?php endif; ?><?php if (isset($_GET['error'])): ?><div class="alert-danger">⚠️ <?=htmlspecialchars($_GET['error'])?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Nama Pengunjung <span class="required-mark">*</span></label>
            <input type="text" name="nama" class="form-control" value="<?= isset($_SESSION['siswa_nama']) ? htmlspecialchars($_SESSION['siswa_nama']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label>Keperluan <span class="required-mark">*</span></label>
            <input type="text" name="keperluan" class="form-control" placeholder="Contoh: Meminjam buku, membaca, tugas sekolah" required>
        </div>
        <button type="submit" class="btn btn-primary">Absen Sekarang</button>
    </form>
</div>

<h3 class="section-title">📝 Daftar Pengunjung Hari Ini</h3>
<div style="max-width:1100px;margin:0 auto 40px;">
    <div class="card">
        <table class="data-table">
            <tr><th>Jam</th><th>Nama</th><th>Keperluan</th></tr>
            <?php if (mysqli_num_rows($data)==0): ?><tr><td colspan="3" style="text-align:center;">Belum ada pengunjung hari ini.</td></tr><?php endif; ?>
            <?php while ($p = mysqli_fetch_assoc($data)): ?>
            <tr><td><?= htmlspecialchars($p['jam']) ?></td><td><?= htmlspecialchars($p['nama']) ?></td><td><?= htmlspecialchars($p['keperluan']) ?></td></tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
<footer class="site-footer">2026 &copy; Perpustakaan Digital SMA NEGERI 1 KAMAL</footer>
</body>
</html>
