<?php
require "koneksi.php";
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$b = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT b.*, k.nama_kategori, d.kode_ddc, d.nama_ddc
    FROM buku b
    LEFT JOIN kategori_buku k ON k.id_kategori=b.id_kategori
    LEFT JOIN klasifikasi_ddc d ON d.id_ddc=b.id_ddc
    WHERE b.id_buku=$id"));

if (!$b) { echo "<p>Data tidak ditemukan.</p>"; exit; }
?>
<span class="close-btn" onclick="tutupDetail()">&times;</span>
<h3>📘 Detail Buku</h3>
<img class="cover-detail" src="assets/uploads/buku/<?= htmlspecialchars($b['cover']) ?>" onerror="this.src='default.png'">
<table>
    <tr><td>Judul Buku</td><td>: <?= htmlspecialchars($b['judul']) ?></td></tr>
    <tr><td>Pengarang</td><td>: <?= htmlspecialchars($b['pengarang']) ?></td></tr>
    <tr><td>Penerbit</td><td>: <?= htmlspecialchars($b['penerbit']) ?></td></tr>
    <tr><td>Tahun Terbit</td><td>: <?= htmlspecialchars($b['tahun_terbit']) ?></td></tr>
    <tr><td>ISBN</td><td>: <?= htmlspecialchars($b['isbn']) ?></td></tr>
    <tr><td>Kode DDC</td><td>: <?= htmlspecialchars(($b['kode_ddc']??'-').' - '.($b['nama_ddc']??'')) ?></td></tr>
    <tr><td>Kategori Buku</td><td>: <?= htmlspecialchars($b['nama_kategori'] ?? '-') ?></td></tr>
    <tr><td>Stok / Rak</td><td>:
        <span class="badge badge-stok">Stok: <?= $b['stok'] ?></span>
        <span class="badge badge-rak"><?= htmlspecialchars($b['rak']) ?></span>
    </td></tr>
</table>
<button class="btn btn-secondary" style="margin-top:12px;" onclick="tutupDetail()">Tutup</button>
