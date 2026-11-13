=========================================================
 PERPUSTAKAAN DIGITAL KELOMPOK 3 - perpustakaan_digital_kelompok3
=========================================================


Isi paket ini sudah digabung dan diperluas dari project perpustakaan_digital_kelompok3.
Tampilan menggunakan nuansa biru/putih seperti screenshot yang diberikan,
dengan dashboard sidebar, kartu statistik, tabel, tombol warna, cover buku,
dan modal detail buku.

INSTALASI XAMPP
---------------
1. Ekstrak folder perpustakaan_digital_kelompok3 ke C:\xampp\htdocs\
2. Jalankan Apache + MySQL di XAMPP.
3. Buka http://localhost/phpmyadmin
4. Import file database.sql.
   File SQL sudah dibuat aman untuk import ulang: tabel lama akan dihapus
   terlebih dahulu, sehingga error #1062 Duplicate entry seperti 'inf02'
   tidak terjadi karena data lama masih tersisa.
5. Jika konfigurasi MySQL berbeda, edit koneksi.php.
6. Buka http://localhost/perpustakaan_digital_kelompok3/

NAMA PAKET
----------
Perpustakaan Digital Kelompok 3

LOGIN DEMO ADMIN
----------------
admin / admin123                 Administrator
pustakawan / pustakawan123       Pustakawan
pegawai / pegawai123             Pustakawan

LOGIN DEMO SISWA
----------------
NISN: 141414
Password: anitas123

FITUR UTAMA
-----------
PUBLIC / SISWA
- Home / Beranda
- Koleksi Buku
- Cari buku berdasarkan judul, penulis, kategori, jenis, ISBN,
  penerbit, DDC atau kata kunci
- Filter semua penulis
- Urutkan terbaru, judul A-Z, stok terbanyak
- Pagination / nomor halaman
- Detail buku dengan cover, pengarang, penerbit, ISBN, DDC, kategori,
  rak dan stok
- Login siswa, register/sign up, logout
- Form peminjaman siswa seperti referensi: pilih beberapa buku (maks. 3 aktif), stok, tanggal pinjam dan batas kembali otomatis
- Lupa/reset password siswa
- Profil siswa
- Riwayat peminjaman siswa: cover, judul, pengarang, status,
  batas kembali, tanggal kembali, denda dan pesan
- Absen/laporan pengunjung

ADMIN / PUSTAKAWAN
- Dashboard
- Statistik jumlah anggota, judul buku, total stok dan admin
- Grafik peminjaman Januari-Desember berdasarkan tahun
- Grafik kas/denda Januari-Desember
- Data admin + profil admin
- Lupa/reset password admin
- Data anggota/siswa: NISN, nama, kelas, jenis kelamin, foto, aksi
- Tambah, edit, hapus, detail anggota
- Riwayat peminjaman per anggota
- Cetak kartu anggota + QR kode anggota
- Data buku + cover
- Tambah, edit, hapus buku
- Stok buku terpisah + status stok habis/menipis/tersedia
- Daftar buku
- Jenis buku
- Jurusan
- Kategori buku
- Kelas
- Klasifikasi DDC
- Sumber buku
- Data peminjaman
- Tambah peminjaman baru
- Pengembalian buku
- Batas pengembalian otomatis
- Status Dipinjam/Dikembalikan
- Denda keterlambatan otomatis (default Rp1.000/hari)
- Data denda + pencarian
- Kas
- Data pengunjung
- Laporan transaksi peminjaman
- Cetak PDF melalui dialog print browser (pilih Save as PDF)
- Kirim/Share laporan melalui Web Share API jika browser mendukung
- Cetak laporan anggota
- Cetak label buku, jumlah cetak 1 atau lebih
- Cetak kartu anggota

FILE CRUD YANG DIMINTA
----------------------
index.php
koneksi.php
tambah.php
hapus.php
edit.php
proses_tambah.php
proses_tambah_buku.php
proses_tambah buku.php   (nama kompatibilitas sesuai permintaan)
proses_edit.php
style.css
README.txt
default.png
database.sql

CATATAN
-------
- default.png dipakai sebagai cover/foto jika belum ada upload.
- Upload cover buku masuk ke assets/uploads/buku/.
- Upload foto anggota masuk ke assets/uploads/anggota/.
- Untuk aplikasi produksi, ubah password demo menjadi password_hash/password_verify,
  gunakan prepared statement, CSRF protection, HTTPS, dan mekanisme reset password
  berbasis email/token.
- Tombol Cetak PDF memakai fitur print browser sehingga tidak memerlukan library
  PDF tambahan. Di Chrome pilih "Save as PDF".
- QR kartu anggota menggunakan qrcode.js dari CDN. Jika internet dimatikan,
  sediakan library QR lokal bila ingin QR tetap aktif.

DATABASE TERPADU
----------------
Database utama: perpustakaan_digital_kelompok3
File database: database.sql

Import database.sql melalui phpMyAdmin. File koneksi.php sudah menggunakan database
perpustakaan_digital_kelompok3 sehingga tidak memakai database proyek lama.

Tabel utama:
admin, anggota, jurusan, kelas, jenis_buku, kategori_buku, klasifikasi_ddc,
sumber_buku, buku, peminjaman, detail_peminjaman, denda, kas, pengunjung,
log_stok_buku, password_reset.

View laporan:
v_riwayat_peminjaman

Aturan transaksi:
- Maksimal 3 buku aktif per siswa.
- Lama peminjaman 7 hari.
- Denda default Rp1.000/hari keterlambatan.
- Stok tersedia berkurang saat peminjaman dan bertambah saat pengembalian.

PEMBARUAN VALIDASI & MENU
--------------------------
- Menu siswa sekarang menampilkan Koleksi Buku, Peminjaman, Riwayat, Absen Pengunjung, Profil, dan Edit Profil.
- Menu admin menampilkan Data Buku, Tambah Buku, Cari Buku, Stok Buku, Anggota, Peminjaman, Denda, Riwayat, seluruh Data Master, transaksi, cetak, profil, dan logout.
- Form penting menggunakan tanda * dan validasi server agar data kosong tidak dapat disimpan.
- Admin dapat mengedit data siswa dari menu Data Anggota.
- Siswa dapat mengedit profil sendiri.
- Password login siswa/admin dapat ditampilkan dengan tombol mata.
- Peminjaman siswa memakai kalender tanggal dan validasi batas pengembalian.
- Absensi pengunjung mewajibkan nama dan keperluan.

VISUAL UPDATE:
- Added assets/images/library-hero.svg: crisp vector library illustration (no blurry raster placeholder).
- Login Siswa/Admin, portal, and public hero use the library illustration while preserving existing functionality.


DESAIN BERANDA REFERENSI
- Beranda dibuat mengikuti contoh: navbar biru tua, hero perpustakaan, panel pencarian, 8 kotak kategori terpisah, dan kartu buku terpisah.
- Cover buku contoh sudah disediakan sebagai SVG tajam di assets/uploads/buku agar tidak buram.
- database.sql sudah diarahkan ke nama cover SVG tersebut.
- Import database.sql ke MySQL sebelum menjalankan aplikasi.

DATABASE TERPADU
Database desain dan struktur utama sudah digabung menjadi satu file database/database.sql.

REVISI PROFIL SISWA
- profil_siswa.php sekarang menampilkan data profil lengkap dalam kartu-kartu terpisah.
- Tersedia Edit Profil dan Ubah/Tambah Data Profil.
- Jika data wajib belum lengkap, tombol Lengkapi Profil muncul.
- Tersedia Hapus Foto Profil; foto dikembalikan ke default.png.
- Profil menampilkan NISN, kelas, jurusan, gender, no. telepon, email, tanggal daftar, alamat, dan total pinjaman.

REVISI TERBARU - PROFIL ADMIN
- Profil Admin sekarang memiliki tampilan detail profil.
- Ada tombol Edit Profil untuk mengubah nama, nomor telepon, email, dan password.
- Ada tombol Hapus Profil dengan konfirmasi.
- Sistem mencegah penghapusan jika hanya tersisa satu akun admin.
- Setelah profil admin dihapus, sesi ditutup dan pengguna diarahkan ke halaman login.
- Relasi transaksi admin menggunakan ON DELETE SET NULL sehingga riwayat transaksi tetap aman.

============================================================
DESAIN TERPADU SESUAI REFERENSI SCREENSHOT
============================================================
- Beranda menggunakan header navy, banner rak buku, kotak pencarian,
  8 kartu kategori terpisah, dan kartu buku terfavorit/terbaru.
- Login Siswa, Login Admin, dan Portal Login menggunakan gaya visual
  yang sama: header navy, latar/banner perpustakaan, kartu putih,
  kolom wajib (*), dan tombol lihat password.
- Konfigurasi desain disimpan bersama database MySQL pada folder:
  database/
  - database.sql
  - update_desain.sql
- Database memiliki tabel ui_theme dan ui_category_cards sehingga
  konfigurasi desain/kartu kategori tersimpan di database yang sama
  dengan data buku, anggota, admin, peminjaman, dan master lainnya.
- Referensi screenshot pengguna disertakan sebagai:
  assets/images/referensi-screenshot-user.jpg
- Gambar banner perpustakaan:
  assets/images/library-hero.svg
