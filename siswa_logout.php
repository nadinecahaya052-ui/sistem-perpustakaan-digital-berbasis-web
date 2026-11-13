<?php
require "koneksi.php";
unset($_SESSION['siswa_id'], $_SESSION['siswa_nama'], $_SESSION['siswa_kelas']);
header("Location: index.php");
exit;
