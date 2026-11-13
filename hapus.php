<?php require 'admin/includes/auth.php'; $id=(int)($_GET['id']??0); header('Location: admin/buku_hapus.php?id='.$id); exit;
