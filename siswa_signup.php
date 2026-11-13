<?php
require "koneksi.php";

if (isset($_SESSION['siswa_id'])) { header("Location: index.php"); exit; }

$daftar_kelas   = ambil_referensi($koneksi, "kelas", "id_kelas", "nama_kelas");
$daftar_jurusan = ambil_referensi($koneksi, "jurusan", "id_jurusan", "nama_jurusan");
$error = isset($_GET['error']) ? $_GET['error'] : "";

// Nilai lama supaya form tidak kosong lagi kalau ada error validasi
$old = $_SESSION['old_signup'] ?? [];
unset($_SESSION['old_signup']);
function v($old, $key) { return htmlspecialchars($old[$key] ?? ''); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Sign Up | Perpustakaan Digital SMA NEGERI 1 KAMAL</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="signup-wrap">
    <div class="signup-card">
        <div class="avatar-icon">🧑</div>
        <h2>Sign Up</h2><p class="muted">Semua kolom bertanda <span class="required-mark">*</span> wajib diisi. Jika masih ada yang kosong, data siswa tidak dapat disimpan.</p>

        <?php if ($error): ?><div class="alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form action="proses_siswa_signup.php" method="POST" id="signupForm" novalidate>
            <div class="form-group">
                <label># Nisn <span class="required-mark">*</span></label>
                <input type="text" name="nisn" class="form-control" value="<?= v($old,'nisn') ?>" required>
            </div>
            <div class="form-group">
                <label>👤 Nama Lengkap <span class="required-mark">*</span></label>
                <input type="text" name="nama" class="form-control" value="<?= v($old,'nama') ?>" required>
            </div>
            <div class="form-group">
                <label>🔒 Password <span class="required-mark">*</span></label>
                <div class="password-wrap"><input id="signupPassword" type="password" name="password" class="form-control" required><button type="button" class="toggle-password" onclick="togglePassword('signupPassword',this)">👁</button></div>
            </div>
            <div class="form-group">
                <label>🔒 Confirm Password <span class="required-mark">*</span></label>
                <div class="password-wrap"><input id="signupConfirm" type="password" name="konfirmasi" class="form-control" required><button type="button" class="toggle-password" onclick="togglePassword('signupConfirm',this)">👁</button></div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Gender <span class="required-mark">*</span></label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="">Choose</option>
                        <option value="Laki-laki" <?= (($old['jenis_kelamin'] ?? '')=='Laki-laki')?'selected':'' ?>>Laki laki</option>
                        <option value="Perempuan" <?= (($old['jenis_kelamin'] ?? '')=='Perempuan')?'selected':'' ?>>Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Kelas <span class="required-mark">*</span></label>
                    <select name="id_kelas" class="form-control" required>
                        <option value="">Choose</option>
                        <?php foreach ($daftar_kelas as $id => $nama): ?>
                            <option value="<?= $id ?>" <?= (($old['id_kelas'] ?? '')==$id)?'selected':'' ?>><?= htmlspecialchars($nama) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Jurusan <span class="required-mark">*</span></label>
                <select name="id_jurusan" class="form-control" required>
                    <option value="">Choose</option>
                    <?php foreach ($daftar_jurusan as $id => $nama): ?>
                        <option value="<?= $id ?>" <?= (($old['id_jurusan'] ?? '')==$id)?'selected':'' ?>><?= htmlspecialchars($nama) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>📞 No Telepon <span class="required-mark">*</span></label>
                <input type="text" name="no_hp" class="form-control" value="<?= v($old,'no_hp') ?>" placeholder="0812345678" required>
            </div>
            <div class="form-group">
                <label>📅 Tanggal Pendaftaran <span class="required-mark">*</span></label>
                <input type="date" name="tgl_daftar" class="form-control" value="<?= v($old,'tgl_daftar') ?: date('Y-m-d') ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Sign Up</button>
            <button type="reset" class="btn btn-warning">Reset</button>

            <div class="link-row">Already have an account? <a href="siswa_login.php">Sign In</a></div>
        </form>
    </div>
</div>
<script>
(function(){const f=document.getElementById('signupForm'); if(!f)return; f.addEventListener('submit',function(e){if(!f.checkValidity()){e.preventDefault();f.reportValidity();}});})();
function togglePassword(id,btn){const i=document.getElementById(id);if(i.type==='password'){i.type='text';btn.textContent='🙈';}else{i.type='password';btn.textContent='👁';}}
</script>
</body>
</html>
